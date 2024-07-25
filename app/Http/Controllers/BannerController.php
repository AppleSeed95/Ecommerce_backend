<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    //
    public function index(){
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Banner List';

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Banners', 'link'=>''];
            // dump([session('')]);exit;
            return view('banners.index', compact('pageTitle', 'title', 'breadcrumb'));
        // }
    }

    public function showBannerFrm(Request $request){
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Banner List';
            $banner = new Banner();
            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Banners', 'link'=> route('banners') ];
            if( !empty( $request->id ) ){
                $breadcrumb[] = ['title'=>'Edit Banner', 'link'=>''];
                $pageTitle = $title = 'Edit Banner';
                $banner = Banner::findOrFail($request->id);
                if(!empty($banner->banner_image)){
                    $banner->banner_image = Storage::url($banner->banner_image);
                    // $banner->banner_image = Storage::get($banner->banner_image);
                }
            }else{
                $breadcrumb[] = ['title'=>'Add Banner', 'link'=>''];
                $pageTitle = $title = 'Add Banner';
            }
            $bannerGroup = Banner::orderBy('banner_group', 'ASC')->pluck('banner_group','banner_group')->toArray();

            // pr($bannerGroup);
            // exit;
            return view('banners.form', compact('pageTitle', 'title', 'breadcrumb', 'banner','bannerGroup'));
        // }
    }

    public function saveBanner(Request $request){
        // pr($request->all());exit;
        $msg='Banner saved succesfully!';
        $validationRules = [
            'banner_name'=>'required|string|max:75',
            'banner_group'=>'required|string',
            'banner_type'=>'required|string',
            'banner_html'=>'min:10',
            'sequence'=>'numeric',
            // 'banner_link'=>'string',
            'banner_image'=>'image|mimes:jpg,png,jpeg,gif,svg,webp',
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'banner_name.required'=>'Banner name is required.',
            'banner_group.required'=>'Banner group is required.',
            // 'display_name.max'=>'Banner name can not be greater than 75 chars.',
            // 'banner_html.string'=>'Banner route is required to st.',
            'banner_html.min'=>'Banner text should be atlease 10 chars.',
            'sequence.number'=>'Banner sequence should be integer.',
            'banner_type.required'=>'Banner type is required',
        ]);
        // print_r($validator->errors());exit;

        if( $validator->fails() ){
            // return $this->sendError('Validation Error', 206, $validator->errors());
            return redirect(route('bannerFrm'))
            ->withErrors($validator)
            ->withInput();
        }
        
        if(!empty($request->id)){
            $msg='Banner updated succesfully!';
            $banner = Banner::findOrFail($request->id);
            // pr($banner);exit;
        }else{
            $banner = new Banner();
        }
        // save the uploaded image 
        if(!empty($request->banner_image)){
            if(!empty($banner->banner_image)){
                Storage::delete($banner->banner_image);
            }
            $path = $request->file('banner_image')->store('public/banner_image');
            $banner->banner_image = $path;
        }
        $banner->banner_name = $request->banner_name;
        $banner->banner_group = $request->banner_group;
        $banner->banner_link = $request->banner_link;
        $banner->banner_html = $request->banner_html;
        $banner->banner_type = $request->banner_type;
        $banner->sequence = $request->sequence;
        $banner->status = $request->status;
        $banner->created_by = Auth::id();
        if($banner->save()){
            return redirect(route('banners'))->withMessage($msg)->withStatus( 'success');
        }else{
            return redirect(route('banners'))->with(['message' => $msg, 'status' => 'danger']);
        }
    }

}
