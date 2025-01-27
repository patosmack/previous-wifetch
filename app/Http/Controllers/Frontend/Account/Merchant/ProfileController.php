<?php

namespace App\Http\Controllers\Frontend\Account\Merchant;


use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User\MerchantInfo;
use App\Models\UserCatalog\UserPartPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $user = Auth::user();
        if($user->is_merchant){
            $merchant = MerchantInfo::available()->where('user_id', '=', $user->id)->where('id', '=', $id)->first();
            return view('frontend.account.merchant.profile', compact('merchant'));
        }
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->is_merchant) {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'country_id' => ['required', 'string', 'max:255', 'exists:countries,id'],
                'parish_id' => ['required', 'string', 'max:255', 'exists:parishes,id'],
               // 'address' => ['required', 'string', 'max:255'],
               // 'description' => ['required', 'string'],
                'email' => ['nullable', 'email', 'string', 'max:255'],
                'contact_email' => ['nullable', 'email', 'string', 'max:255'],
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $merchant_id = $request->get('merchant_id');
            $merchant = MerchantInfo::available()->where('user_id', '=', $user->id)->where('id', '=', $merchant_id)->first();
            if(!$merchant){
                return redirect()->back()->withErrors(['error' => 'Your Shop could not be found'])->withInput();
            }



            $merchant->name = $request->get('name');
            $merchant->country_id = $request->get('country_id');
            $merchant->parish_id = $request->get('parish_id');
            $merchant->address = $request->get('address');
            $merchant->phone = $request->get('phone');
            $merchant->email = $request->get('email');
            $merchant->description = $request->get('description');
            $merchant->disclaimer = $request->get('disclaimer');

            $merchant->contact_name = $request->get('contact_name');
            $merchant->contact_email = $request->get('contact_email');
            $merchant->contact_phone = $request->get('contact_phone');

            $allow_custom_items = $request->get('allow_custom_items', false);
            if($allow_custom_items === 'yes'){
                $merchant->allow_custom_items = 1;
            }else{
                $merchant->allow_custom_items = 0;
            }

            $merchant->save();

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
                }catch (\Exception $exception){
                    return redirect()->back()->withErrors(['error' => 'There was a problem uploading the business logo'])->withInput();
                }
            }
            if ($merchant->save()) {
                return redirect()->back();
            }
            return redirect()->back()->withErrors(['error' => 'Your Business information could not be saved'])->withInput();
        }
        return redirect()->back()->withErrors(['error' => 'Your account doesn\'t have permissions to access this area'])->withInput();
    }
}
