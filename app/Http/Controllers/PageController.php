<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Page;
use App\Models\Category;
use App\Models\Collection;
use App\Models\ProductImage;
use App\Models\ProductToCategory;
use App\Models\ProductToCollections;
use Auth;


class PageController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Page List';

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Pages', 'link'=>''];
            // dump([session('')]);exit;
            return view('pages.index', compact('pageTitle', 'title', 'breadcrumb'));
        // }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id='')
    {
        // if( Auth::user()->can(getMenuPermission())){
            // dd(session()->get('errors'));
            $pageTitle = $title = 'Page';
            $page = new Page();
            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Add Page', 'link'=> route('pages') ];
            if( !empty( $id ) ){
                $breadcrumb[] = ['title'=>'Edit Page', 'link'=>''];
                $pageTitle = $title = 'Edit Page';
                $page = Page::findOrFail($id);
            }else{
                $breadcrumb[] = ['title'=>'Add Page', 'link'=>''];
                $pageTitle = $title = 'Add Page';
            }
            $category = new Category;
            $categories = $category->getCategories();

            $category = new Collection;
            $collections = $category->getCollections();
            
            return view('pages.form', compact('pageTitle', 'title', 'breadcrumb', 'page','categories', 'collections'));
        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $msg='Page saved succesfully!';
        $validationRules = [
            'page_title'=>'required|string|max:250',
            'page_slug'=>'required|string',
            'meta_title'=>'max:180',
            'meta_desc'=>'max:350',
            'description'=>'string',
            
            'image.*.image'=>'image|mimes:jpg,png,jpeg,gif,svg,webp',
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'page_title.required'=>'Product name is required.',
            'page_title.max'=>'Product name should be maximum 250 charactors.',
            'page_slug.max'=>'Product slug should be maximum 250 charactors.',
            'meta_title'=>'Meta title can not be greater than 180 charactors.',
            'meta_desc.max'=>'Meta description can have maximum 250 charactors.',
            'description.min'=>'Product description should be atlease 10 chars.',
            
        ]);
        // pr([$request->all(), $validator->fails()]);exit;

        if( $validator->fails() ){
            // return $this->sendError('Validation Error', 206, $validator->errors());
            // dd($validator->errors());
            return redirect(route('save-page'))
            ->withErrors($validator)
            ->withInput();
        }
        
        if(!empty($request->id)){
            $msg='Page updated succesfully!';
            $page = Page::findOrFail($request->id);
            // pr($page);exit;
            // $page->modified_by = Auth::id();
        }else{
            $page = new Page();
            $page->page_slug = $page->getSlug($request->page_title);
        }
        
        $page->page_title = $request->page_title;
        $page->meta_title = $request->meta_title;
        $page->meta_desc = $request->meta_desc;
        $page->description = $request->description;
        $page->tags = $request->tags;
        $page->status = $request->status;
        $image = $request->image;
        // dd($image);
        if( !empty( $image ) ){
            $page->image = $image->store('public/page_image');
        }

        if( $page->save() ){
            return redirect( route('pages') )->withMessage($msg)->withStatus( 'success');
        }else{
            return redirect( route('pages') )->with(['message' => $msg, 'status' => 'danger']);
        }
    }
}
