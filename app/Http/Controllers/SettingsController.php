<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Auth;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Menu;

class SettingsController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Index method
     *
     * @return Illuminate\Http\Request|null
     */
    public function index(Request $request){
        $title = $pageTitle = 'All Settings';
        $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
        $breadcrumb[]= ['title'=>'Settings', 'link'=>''];
        // $setting = Setting::where('status',1)->pluck('config_value','config_key');
        $settingObj = new Setting;
        $setting = $settingObj->settingList();

        // get the category listing 
        $categoryModel = new Category;
        $productModel = new Product;

        // get the routes 
        $routeCollection = Route::getRoutes();
        $availableRoutes['categories'] = $categoryModel->getCategoriesMenus();
        $availableRoutes['products'] = $productModel->getProductMenus();
        foreach($routeCollection as $value){
            // dump($value->getName());
            // dump($value->methods());
            // dump($value->uri());
            if( $value->getName() 
                // && (Str::contains($value->getName(), 'api.')) 
                // && (!Str::contains($value->getName(), 'api/')) 
                && (!Str::contains($value->uri(),'_ignition')) 
                && ($value->methods()[0] == 'GET') 
                && (!Str::contains($value->uri(), '/{'))  // to remove the get method parameters form the url
            ){
                $availableRoutes[] = $value->getName();
            }
        }

        // Menu Groups
        $menu = new Menu;
        $menuGroups =  $menu->getMenuGroups();

        $categories = Category::select('id', 'category_name', 'slug')->where(['status'=> 1, 'is_deleted'=>0])->orderBy('category_name', 'ASC')->get();

        // dd($menuGroups);
        return view('settings/form', compact(['title', 'pageTitle', 'breadcrumb', 'setting','categories', 'availableRoutes', 'menuGroups']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->site_logo);
        // pr($request->all());exit;

        $msg='Product saved succesfully!';
        $validationRules = [
            'site_title'=>'required|string|max:250',
            'seo_title'=>'required|string|max:160',
            // 'seo_description'=>'max:160',

            // 'site_logo'=>'image|mimes:jpg,png,jpeg,gif,svg,webp',
            // 'site_favicon'=>'image|mimes:jpg,png,jpeg,gif,svg,webp',
            // 'site_default_image'=>'image|mimes:jpg,png,jpeg,gif,svg,webp',
            // 'site_loader_image'=>'image|mimes:jpg,png,jpeg,gif,svg,webp'
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'site_title.required'=>'Site title is required.',
            'site_title.max'=>'Site title should be maximum 250 charactors.',
            'seo_title.required'=>'SEO title is required.',
            'seo_title.max'=>'SEO title should be maximum 60 charactors.',
            // 'seo_description.max'=>'Meta description can have maximum 250 charactors.',

            // 'site_logo'=>'Please select the image with extension jpg,png,jpeg,gif,svg,webp',
            // 'site_favicon'=>'Please select the image with extension jpg,png,jpeg,gif,svg,webp',
            // 'site_default_image'=>'Please select the image with extension jpg,png,jpeg,gif,svg,webp',
            // 'site_loader_image'=>'Please select the image with extension jpg,png,jpeg,gif,svg,webp'
        ]);

        if( $validator->fails() ){
            // return $this->sendError('Validation Error', 206, $validator->errors());
            // dd($validator->errors());
            return redirect(route('settings'))
            ->withErrors($validator)
            ->withInput();
        }
        // dd($request->all() );
        $settings= new Setting;
        foreach($request->all() as $key=>$value){
            if(in_array($key, ['_token', 'save'])){
                continue;
            }
            $setting = $settings->getConfigValue($key);
            if(!empty($setting->id)){
                $setting->config_value = $value;
            }else{
                $setting = new Setting;
                if( is_array($value) ){
                    $setting->value_type = 'json';
                    $value = json_encode($value);
                }
                $setting->config_value = $value;
                $setting->config_key = $key;
            }
            $setting->save();
            // dd($setting);
        }

        $settings = $this->saveImage($request->site_logo,'site_logo');
        $settings = $this->saveImage($request->site_favicon,'site_favicon');
        $settings = $this->saveImage($request->site_default_image,'site_default_image');
        $settings = $this->saveImage($request->site_loader_image,'site_loader_image');
        $settings = $this->saveImage($request->our_clients,'our_clients');
        $settings = $this->saveImage($request->about_bollard_collection,'about_bollard_collection');
        
        return redirect(route('settings'))
            ->withSuccess('Settings Saved succesfully.')->withSettings($settings);

    }

     private function saveImage($image, $key){
        // save the uploaded image 
        // dd($image);
        if(!empty($image) && !empty($key)){
            $settings= new Setting;
            $setting = $settings->getConfigValue($key);

            if(empty($setting->id)){
                $setting = new Setting;
                $setting->config_key = $key;
                $setting->value_type = 'img';
                $ourClientImg = json_decode($setting->config_value, 1);
            }

            if(is_array($image)){
                foreach($image as $img){
                    $clients[] = $img->store('public/image');
                }
                $setting->config_value = json_encode($clients);
                $setting->value_type = 'imgJson';
            }else{
                $setting->config_value = $image->store('public/image');
                $setting->value_type = 'img';
            }
            $ourClientImg = array_merge($ourClientImg, $clients);
            $setting->save();
        }
        return [$setting->value_type=>$ourClientImg];
    }


}
