<?php

namespace App\Http\Controllers\Backend\Category;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Merchant\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siteCategories = Category::withCount('merchants')->sortedByOrder('DESC')->get();
        return view('backend.category.categories', compact('siteCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'friendly_url' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id = $request->get('category_id');
        $name = $request->get('name');
        $friendly_url = $request->get('friendly_url');

        if($friendly_url){
            $friendly_url = Helper::slug($friendly_url);
        }

        $order = (int)$request->get('order');

        $friendly_url_category = Category::where('friendly_url', '=', $friendly_url)->where('id', '!=', $id)->first();
        if($friendly_url_category){
            return redirect()->back()->withErrors(['error' => 'The friendly url already exists']);
        }

        $category = Category::find($id);
        if(!$category) {
            $category = new Category();
        }
        $category->name = $name;
        $category->friendly_url = $friendly_url;
        $category->order = $order;

        if ($request->hasFile('icon')) {
            $attachment = $request->file('icon');
            $uploadBasepath =  rtrim('assets/uploads/category', '/\\');
            $attachment_file_name = Helper::slug($category->id . '-' . Str::random(55)) . '.' .  strtolower($attachment->getClientOriginalExtension());
            $attachment->move($uploadBasepath, $attachment_file_name);
            $old_image = $category->icon;
            $category->icon = $uploadBasepath . '/' .$attachment_file_name;
            try{
                $img = Image::make(file_get_contents(public_path($category->icon)));
                $img->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path($category->icon), 90);
                if($old_image and File::exists(public_path($old_image))){
                    File::delete(public_path($old_image));
                }
                $saved = $category->save();
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['error' => 'There was a problem uploading the category icon'])->withInput();
            }
        }else{
            if(!$id){
                return redirect()->back()->withErrors(['error' => 'The category icon is required'])->withInput();
            }
            $saved = $category->save();
        }
        if($saved){
            return redirect(route('backend.category.list'))->with(['success' => 'Category saved successfully']);
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem saving the site category'])->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggle($id)
    {
        $category = Category::where('id', '=', $id)->first();
        if($category){
            $old_status = $category->enabled;
            $category->enabled = !$old_status;
            if($category->save()){
                $message = 'The site category was disabled successfully';
                if(!$old_status){
                    $message = 'The site category was enabled successfully';
                }
                return redirect(route('backend.category.list'))->with(['success' => $message]);
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem modifying the site category status']);

    }
}
