<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Collection;
use App\Models\ProductImage;
use App\Models\ProductToCategory;
use App\Models\ProductToCollections;
use Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Blog List';

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Blogs', 'link'=>''];
            // dump([session('')]);exit;
            return view('blogs.index', compact('pageTitle', 'title', 'breadcrumb'));
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
            $pageTitle = $title = 'Blog';
            $blog = new Blog();
            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Add Blog', 'link'=> route('blogs') ];
            if( !empty( $id ) ){
                $breadcrumb[] = ['title'=>'Edit Blog', 'link'=>''];
                $pageTitle = $title = 'Edit Blog';
                $blog = Blog::findOrFail($id);
            }else{
                $breadcrumb[] = ['title'=>'Add Blog', 'link'=>''];
                $pageTitle = $title = 'Add Blog';
            }
            $category = new Category;
            $categories = $category->getCategories();

            $category = new Collection;
            $collections = $category->getCollections();
            
            return view('blogs.form', compact('pageTitle', 'title', 'breadcrumb', 'blog','categories', 'collections'));
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
        $msg='Blog saved succesfully!';
        $validationRules = [
            'blog_title'=>'required|string|max:250',
            'blog_slug'=>'required|string',
            'meta_title'=>'max:180',
            'meta_desc'=>'max:350',
            'description'=>'string',
            
            'image.*.image'=>'image|mimes:jpg,png,jpeg,gif,svg,webp',
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'blog_title.required'=>'Product name is required.',
            'blog_title.max'=>'Product name should be maximum 250 charactors.',
            'blog_slug.max'=>'Product slug should be maximum 250 charactors.',
            'meta_title'=>'Meta title can not be greater than 180 charactors.',
            'meta_desc.max'=>'Meta description can have maximum 250 charactors.',
            'description.min'=>'Product description should be atlease 10 chars.',
            
        ]);
        // pr([$request->all(), $validator->fails()]);exit;

        if( $validator->fails() ){
            // return $this->sendError('Validation Error', 206, $validator->errors());
            // dd($validator->errors());
            return redirect(route('save-blog'))
            ->withErrors($validator)
            ->withInput();
        }
        
        if(!empty($request->id)){
            $msg='Blog updated succesfully!';
            $blog = Blog::findOrFail($request->id);
            // pr($blog);exit;
            // $blog->modified_by = Auth::id();
        }else{
            $blog = new Blog();
            $blog->blog_slug = $blog->getSlug($request->blog_title);
        }
        
        $blog->blog_title = $request->blog_title;
        $blog->meta_title = $request->meta_title;
        $blog->meta_desc = $request->meta_desc;
        $blog->description = $request->description;
        $blog->tags = $request->tags;
        $blog->status = $request->status;
        $image = $request->image;
        // dd($image);
        if( !empty( $image ) ){
            $blog->image = $image->store('public/blog_image');
        }

        if( $blog->save() ){
            return redirect( route('blogs') )->withMessage($msg)->withStatus( 'success');
        }else{
            return redirect( route('blogs') )->with(['message' => $msg, 'status' => 'danger']);
        }
    }



}
