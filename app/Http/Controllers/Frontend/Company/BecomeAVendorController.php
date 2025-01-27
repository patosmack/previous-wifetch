<?php

namespace App\Http\Controllers\Frontend\Company;


use App\Helpers\CartHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User\MerchantInfo;
use App\Models\User\User;
use App\Models\User\UserAddress;
use App\Notifications\Merchant\NewMerchantNotification;
use App\Notifications\Order\OrderPlacedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BecomeAVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.company.become_a_vendor');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
       // return redirect()->back()->withInput();

        $user = Auth::user();

        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],

            'country_id' => ['required', 'string', 'max:255', 'exists:countries,id'],
            'parish_id' => ['required', 'string', 'max:255', 'exists:parishes,id'],
        ];

        if(!$user){
            $validationRules[ 'email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
            $validationRules[ 'password'] = ['required', 'string', 'min:8', 'confirmed'];
        }else{
            $validationRules[ 'email'] = ['required', 'string', 'email', 'max:255'];
        }

        $validator = Validator::make($request->all(), $validationRules, [
            'country_id.exists' => 'Select a valid Country',
            'parish_id.exists' => 'Select a valid Parish'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('status', 'logo', 'cover_image', 'friendly_url', 'delivery_fee', 'service_fee');

        if(!$user){
            $user = $this->create($request->all());
            if(!$user){
                return redirect()->back()->withErrors(['error' => 'Your login credentials could not be created, try again'])->withInput();
            }
            Auth::guard()->login($user);
        }

        $data['user_id'] = $user->id;

        $merchant = MerchantInfo::create($data);
        if($merchant){
            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $uploadBasepath =  rtrim('assets/uploads/merchant', '/\\');
                $attachment_file_name = Helper::slug($merchant->id . '-' . Str::random(55)) . '.' .  strtolower($attachment->getClientOriginalExtension());
                $attachment->move($uploadBasepath, $attachment_file_name);
                $old_image = $merchant->logo;
                $merchant->logo = $uploadBasepath . '/' .$attachment_file_name;
                try{
                    $img = Image::make(file_get_contents(public_path($merchant->logo)));
                    $img->resize(1000, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save(public_path($merchant->logo), 90);
                    if($old_image and File::exists(public_path($old_image))){
                        File::delete(public_path($old_image));
                    }
                    $merchant->save();
                }catch (\Exception $exception){
                    return redirect()->back()->withErrors(['error' => 'There was a problem uploading the business logo'])->withInput();
                }
            }
            $merchant->friendly_url = Helper::slug(Helper::stripString($data['name'])) . '-' . $merchant->id;
            $merchant->save();
            $user->is_merchant = 1;
            $user->save();




//
//            $details = [
//                'greeting' => 'Hi ' . $order->user->name,
//                'thanks' => 'Thank you for using ' . env('APP_NAME'),
//                'order_id' => $order->id,
//                'transaction_id' => $order->transaction_id,
//                'tableHeader' => [
//                    'Product',
//                    'Qty',
//                    'Price'
//                ],
//            ];
//
//
//            $details['subject'] = 'Order near destination';
//            $details['body'] = 'The order is near destination';
//            $details['actionText'] = 'View your orders';
//            $details['actionURL'] =  route('account.orders');
//

            $emailDetails = [
                'greeting' => 'New merchant waiting for approval',
                'body' => $merchant->name . ' just requested to be a merchant on WiFetch platform and is waiting for your approval',
                'actionText' => 'View profile',
                'actionURL' => route('backend.merchant.profile', $merchant->id),
                'merchant_id' => $merchant->id,
            ];

//            $admin_0 = User::where('email', '=', 'patosmack@gmail.com')->first();
//            if($admin_0){
//                $admin_0->notify(new NewMerchantNotification($emailDetails));
//            }

            $admin_1 = User::where('email', '=', 'lily@wifetch.com')->first();
            if($admin_1){
                $admin_1->notify(new NewMerchantNotification($emailDetails));
            }
            $admin_2 = User::where('email', '=', 'lily@caribound.com')->first();
            if($admin_2){
                $admin_2->notify(new NewMerchantNotification($emailDetails));
            }
            $admin_3 = User::where('email', '=', 'sophie@wifetch.com')->first();
            if($admin_3){
                $admin_3->notify(new NewMerchantNotification($emailDetails));
            }

            return redirect(route('account.merchant.shops'))->with(['success' => 'Thank you for becoming a merchant']);
        }
//        $merchant


        return redirect()->back()->withErrors(['error' => 'Your merchant Account could not be created, try again'])->withInput();


        //return redirect()->back()->withInput();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User\User
     */
    protected function create(array $data)
    {
        /*
        * Create default Address
        */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $unsessionedCart = CartHelper::getCart();
        if($unsessionedCart){
            $unsessionedCart->user_id = $user->id;
            $unsessionedCart->user_token = null;
            $unsessionedCart->save();
            if($unsessionedCart && $unsessionedCart->items && count($unsessionedCart->items) > 0){
                $cartWithItems = true;
            }
            if($cartWithItems){
                Session::flash('open_cart_no_animation', true);
            }
        }

        if($this->createDefaultAddress($user, $data)){
            return $user;
        }
        return null;
    }

    /**
     * Create a new user instance after a valid registration.
     * @param  User  $user
     * @param  array  $data
     * @return bool
     */
    protected function createDefaultAddress(User $user, array $data){
        $userAddress = new UserAddress();
        $userAddress->name = 'Primary';
        $userAddress->user_id = $user->id;
        $userAddress->parish_id = $data['parish_id'];
        $userAddress->country_id = $data['country_id'];
        $userAddress->current = 1;
        $userAddress->enabled = 1;
        return $userAddress->save();
    }


//    /**
//     * Update
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function update(Request $request, $merchant_id)
//    {
//        $merchant = MerchantInfo::where('id', '=', $merchant_id)->first();
//        if(!$merchant){
//            return redirect()->back()->withErrors(['error' => 'The merchant could not be found'])->withInput();
//        }
//
//        $validator = Validator::make($request->all(), [
//            'name' => ['required', 'string', 'max:255'],
//            'country_id' => ['required', 'string', 'max:255', 'exists:countries,id'],
//            'parish_id' => ['required', 'string', 'max:255', 'exists:parishes,id'],
//        ], [
//            'country_id.exists' => 'Select a valid Country',
//            'parish_id.exists' => 'Select a valid Parish'
//        ]);
//
//        if ($validator->fails()) {
//            return redirect()->back()->withErrors($validator)->withInput();
//        }
//
//        if ($request->hasFile('attachment')) {
//            $attachment = $request->file('attachment');
//            $uploadBasepath =  rtrim('assets/uploads/merchant', '/\\');
//            $attachment_file_name = Helper::slug($merchant->id . '-' . Str::random(55)) . '.' .  strtolower($attachment->getClientOriginalExtension());
//            $attachment->move($uploadBasepath, $attachment_file_name);
//            $old_image = $merchant->logo;
//            $merchant->logo = $uploadBasepath . '/' .$attachment_file_name;
//            try{
//                $img = Image::make(file_get_contents(public_path($merchant->logo)));
//                $img->resize(1000, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//                $img->save(public_path($merchant->logo), 90);
//                if($old_image and File::exists(public_path($old_image))){
//                    File::delete(public_path($old_image));
//                }
//                $merchant->save();
//            }catch (\Exception $exception){
//                return redirect()->back()->withErrors(['error' => 'There was a problem uploading the business logo'])->withInput();
//            }
//        }
//
//
//        if($merchant->update($request->except('status', 'logo', 'cover_image', 'friendly_url'))){
//            return redirect()->back()->with(['success' => 'The merchant information was saved successfully']);
//        }
//        return redirect()->back()->withErrors(['error' => 'The merchant could not be saved, try again'])->withInput();
//
//    }

}
