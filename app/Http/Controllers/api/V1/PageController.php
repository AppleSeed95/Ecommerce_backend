<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;


use App\Models\Setting;
use App\Models\Menu;
use App\Models\Page;
use App\Models\ContactUs;
use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;

class PageController extends ApiController
{
    //

    public function aboutBollard(Request $request){
        $setting = new Setting;
        $keys=[
            'about_au_bollard',
            'about_bollard_collection_link',
            'about_bollard_video',
            'about_bollard_collection',
            'about_bollard_video_title'
        ];
        $settings = $setting->getConfigs($keys);
        return $this->sendResponse($settings, 'Homepage About Bollard');
    }

    public function footerData(Request $request){
        $setting = new Setting;
        $keys=[
            'footer_contact',
            'footer_links'
        ];
        $settings = $setting->getConfigs($keys);

        $menu = new Menu;
        $settings['footer_links'] = $menu->getMenuTree($parent=0, $menuArray=[], $settings['footer_links']);

        return $this->sendResponse($settings, 'Footer Content');
    }

    public function getPages(Request $request, $slug){
        $query = Page::select()->where(['status'=> 1, 'is_deleted'=>0])->where('page_slug', 'LIKE', $slug)->get();
        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');
        foreach($query as $key=>$row){
            if(!empty($row->image)){
                $query[$key]->image = asset(Storage::url($row->image));
            }else{
                $query[$key]->image = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
        }
        return $this->sendResponse($query, 'Page');
    }

    //
    public function listPages(Request $request){
        $query = Page::select()->where(['is_deleted'=>0]);

        return DataTables::of($query)
        ->editColumn('image', function($row){
            return Storage::url($row->image);
        })
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= '<a href="'.route('pageFrm').'/'.$row->id.'" title="Edit Product" class="btn btn-primary btn-xs mx-1"><i class="fas fa-pen"></i></a>';
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyPage')) .")' title='Delete Menu' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
            // ->orderColumns(['product.display_name', 'product.modified_at'], 'desc')
        ->rawColumns(['action', 'image'])
        ->make(true);
    }

    //
    public function latestPages(Request $request){
        $query = Page::select()->where(['is_deleted'=>0]);
        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');

        foreach($query as $key=>$row){
            if(!empty($row->image)){
                $query[$key]->image = asset(Storage::url($row->image));
            }else{
                $query[$key]->image = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
        }
        return $this->sendResponse($query, 'category');
    }

    public function homepageListing(Request $request){
        $query = Page::select()->where(['status'=> 1, 'is_deleted'=>0])->limit(6)->get();
        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');
        foreach($query as $key=>$row){
            if(!empty($row->image)){
                $query[$key]->image = asset(Storage::url($row->image));
            }else{
                $query[$key]->image = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
            
        }
        return $this->sendResponse($query, 'category');
    }

    public function getPage(Request $request, $slug){
        $query = Page::select()->where(['status'=> 1, 'is_deleted'=>0])->where('page_slug', 'LIKE', $slug)->get();
        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');
        foreach($query as $key=>$row){
            if(!empty($row->image)){
                $query[$key]->image = asset(Storage::url($row->image));
            }else{
                $query[$key]->image = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
        }
        return $this->sendResponse($query, 'category');
    }

    public function globleSearch(Request $request){
        // print_r($request->all());exit;
        $page = new Page;
        $blog = new Blog;
        $product = new Product;
        $category = new Category;
        $result=[];
        $result['categories']=$category->search($request->q);
        // pr($result['categories']);
        foreach($result['categories']['rows'] as $index=>$cat){
            $result['categories']['rows'][$index]->image = asset(($cat->image)?Storage::url($cat->image):'img/image.png');
            $result['categories']['rows'][$index]->desc = strip_tags($cat->desc);
        }

        $result['products']=$product->search($request->q);
        foreach($result['products']['rows'] as $index=>$prod){
            $result['products']['rows'][$index]->image = asset(($prod->image)?Storage::url($prod->image):'img/image.png');
            $result['products']['rows'][$index]->desc = strip_tags($prod->desc);
        }

        $result['pages']=$page->search($request->q);
        foreach($result['pages']['rows'] as $index=>$page){
            $result['pages']['rows'][$index]->image = asset(($page->image)?Storage::url($page->image):'img/image.png');
            $result['pages']['rows'][$index]->desc = strip_tags($page->desc);
        }
        $result['totals'] = $result['categories']['total'] + $result['pages']['total'] + $result['products']['total'];

        return $this->sendResponse($result, 'Search Result');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = Page::findOrFail($request->menuId);
        $record->is_deleted = 1;
        if($response = $record->save()){
            $response = $this->sendResponse($record, 'Selected page is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }
}
