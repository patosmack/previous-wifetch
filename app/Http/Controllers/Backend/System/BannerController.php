<?php

namespace App\Http\Controllers\Backend\System;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\System\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siteBanners = Banner::sortedByOrder('DESC')->get();
        return view('backend.system.banners', compact('siteBanners'));
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
            'target' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id = $request->get('banner_id');
        $name = $request->get('name');
        $target = $request->get('target');
        $order = (int)$request->get('order');

        $banner = Banner::find($id);
        if(!$banner) {
            $banner = new Banner();
        }
        $banner->name = $name;
        $banner->target = $target;
        $banner->order = $order;


        $image_required = false;
        if (!$request->hasFile('image') && !$banner->image) {
            $image_required = true;
        }
        if (!$request->hasFile('image_mobile') && !$banner->image_mobile) {
            $image_required = true;

        }
        if($image_required){
            return redirect()->back()->withErrors(['error' => 'The banner image is required'])->withInput();
        }

        if ($request->hasFile('image')) {
            $attachment = $request->file('image');
            $uploadBasepath =  rtrim('assets/uploads/banner', '/\\');
            $attachment_file_name = Helper::slug($banner->id . '-' . Str::random(55)) . '.' .  strtolower($attachment->getClientOriginalExtension());
            $attachment->move($uploadBasepath, $attachment_file_name);
            $old_image = $banner->image;
            $banner->image = $uploadBasepath . '/' .$attachment_file_name;
            try{
                $img = Image::make(file_get_contents(public_path($banner->image)));
//                $img->resize(1000, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
                $img->save(public_path($banner->image), 100);
                if($old_image and File::exists(public_path($old_image))){
                    File::delete(public_path($old_image));
                }
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['error' => 'There was a problem uploading the desktop banner image'])->withInput();
            }
        }

        if ($request->hasFile('image_mobile')) {
            $attachment = $request->file('image_mobile');
            $uploadBasepath =  rtrim('assets/uploads/banner', '/\\');
            $attachment_file_name = Helper::slug($banner->id . '-' . Str::random(55)) . '.' .  strtolower($attachment->getClientOriginalExtension());
            $attachment->move($uploadBasepath, $attachment_file_name);
            $old_image = $banner->image_mobile;
            $banner->image_mobile = $uploadBasepath . '/' .$attachment_file_name;
            try{
                $img = Image::make(file_get_contents(public_path($banner->image_mobile)));
//                $img->resize(1000, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
                $img->save(public_path($banner->image_mobile), 100);
                if($old_image and File::exists(public_path($old_image))){
                    File::delete(public_path($old_image));
                }
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['error' => 'There was a problem uploading the mobile banner image'])->withInput();
            }
        }



        $saved = $banner->save();
        if($saved){
            return redirect(route('backend.banner.list'))->with(['success' => 'Banner saved successfully']);
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem saving the site banner'])->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggle($id)
    {
        $banner = Banner::where('id', '=', $id)->first();
        if($banner){
            $old_status = $banner->enabled;
            $banner->enabled = !$old_status;
            if($banner->save()){
                $message = 'The site banner was disabled successfully';
                if(!$old_status){
                    $message = 'The site banner was enabled successfully';
                }
                return redirect(route('backend.banner.list'))->with(['success' => $message]);
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem modifying the site banner status']);

    }
}
