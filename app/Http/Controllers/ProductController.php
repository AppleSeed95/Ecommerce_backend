<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;
use App\Models\Collection;
use App\Models\ProductImage;
use App\Models\ProductToCategory;
use App\Models\ProductToCollections;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Product List';

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Products', 'link'=>''];
            // dump([session('')]);exit;
            return view('products.index', compact('pageTitle', 'title', 'breadcrumb'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if( Auth::user()->can(getMenuPermission())){
            // dd(session()->get('errors'));
            $pageTitle = $title = 'Product List';
            $product = new Product();
            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Products', 'link'=> route('products') ];
            if( !empty( $request->id ) ){
                $breadcrumb[] = ['title'=>'Edit Product', 'link'=>''];
                $pageTitle = $title = 'Edit Product';
                $product = Product::findOrFail($request->id);
                if(!empty($product->product_image)){
                    $product->product_image = Storage::url($product->product_image);
                    // $product->product_image = Storage::get($product->product_image);
                }
            }else{
                $breadcrumb[] = ['title'=>'Add Product', 'link'=>''];
                $pageTitle = $title = 'Add Product';
            }
            $category = new Category;
            $categories = $category->getCategories();

            $category = new Collection;
            $collections = $category->getCollections();
            // dump($product);
            // dump($product->productToCategories);
            $productCategories = array_column($product->productToCategories->toArray(), 'category_id');
            $productCollections = array_column($product->ProductToCollections->toArray(), 'collection_id');
            
            // foreach($product->productImages as $k=>$docs){
            //     if(!empty($docs->doc_type) && 'doc'==$docs->doc_type){
            //         unset($product->productImages[$k]);
            //         $product->productDocs
            //     }
            // }

            // dump($product->productImagesOnly);
            // dump($product->productDocsOnly);
            // dd($product->productImages);

            // exit;
            return view('products.form', compact('pageTitle', 'title', 'breadcrumb', 'product','categories','productCategories', 'collections', 'productCollections'));
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
        // pr($request->all());exit;
        $msg='Product saved succesfully!';
        $validationRules = [
            'product_name'=>'required|string|max:250',
            'product_slug'=>'required|string',
            'meta_title'=>'max:180',
            'sku'=>'required|string',
            'meta_desc'=>'max:350',
            'vendor_name'=>'max:150',
            'product_desc'=>'string',
            'price'=>'numeric|min:0',
            'special_price'=>'numeric|min:0',
            'product_image.*.image'=>'image|mimes:jpg,png,jpeg,gif,svg,webp',
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'product_name.required'=>'Product name is required.',
            'product_name.max'=>'Product name should be maximum 250 charactors.',
            'product_slug.max'=>'Product slug should be maximum 250 charactors.',
            'meta_title'=>'Meta title can not be greater than 180 charactors.',
            'meta_desc.max'=>'Meta description can have maximum 250 charactors.',
            'sku.required'=>'Product SKU is required',
            'vendor_name.max'=>'Vendor name should be less than 150 charactors.',
            'product_desc.min'=>'Product description should be atlease 10 chars.',
            'price.numeric'=>'Product price should be numeric.',
            'price.min'=>'Product price should be greater than 0.',
            'special_price.numeric'=>'Product special price should be numeric.',
            'special_price.min'=>'Product special price should be greater than 0.',
        ]);

        if( $validator->fails() ){
            // return $this->sendError('Validation Error', 206, $validator->errors());
            // dd($validator->errors());
            return redirect(route('productFrm'))
            ->withErrors($validator)
            ->withInput();
        }
        
        if(!empty($request->id)){
            $msg='Product updated succesfully!';
            $product = Product::findOrFail($request->id);
            // pr($product);exit;
            $product->modified_by = Auth::id();
        }else{
            $product = new Product();
            $product->product_slug = $product->getSlug($request->product_name);
        }
        
        $product->product_name = $request->product_name;
        $product->meta_title = $request->meta_title;
        $product->meta_desc = $request->meta_desc;
        $product->sku = $request->sku;
        $product->vendor_name = $request->vendor_name;
        $product->product_desc = $request->product_desc;
        $product->price = $request->price;
        $product->special_price = $request->special_price;
        $product->status = $request->status;
        $product->is_featured = $request->is_featured;
        if( $product->save() ){
            // save the images 
            $this->saveImages($request, $product->id);
            // save the images 
            $this->saveDocs($request, $product->id);
            // save the categories
            $this->saveCategories($request, $product->id);
            // save the catalogue
            $this->saveCollections($request, $product->id);
            return redirect( route('products') )->withMessage($msg)->withStatus( 'success');
        }else{
            return redirect( route('products') )->with(['message' => $msg, 'status' => 'danger']);
        }
    }


    private function saveImages($request, $productId){
        // save the uploaded image 
        if(!empty($request->product_image)){

            $imgPath = '';

            // if(!empty($product->product_image)){
            //     Storage::delete($product->product_image);
            // }
            
            // dd($request->all());
            // if( !empty( $request->file( 'product_image' ) ) ){
                $productImges = [];
                // dump($request->product_image);
                $save = false;
                foreach( $request->product_image as $key => $image ){
                    if( !empty( $image['image'] ) || !empty($image['id']) ){
                        $save = true;
                    }

                    // dd($image);
                    if(!empty($image['id'])){
                        $productImges = ProductImage::findOrFail($image['id']);
                        // dd($productImges);
                    }else{
                        $productImges = new ProductImage;
                    }

                    $productImges->product_id = $productId;
                    // $productImges->image_path = $request->file($image['image'])->store('public/product_image');
                    $productImges->image_title = $image['title'];
                    // $productImges->is_default = $image['default'];
                    $productImges->doc_type ='img';
                    if( !empty( $image['image'] ) ){
                        $productImges->image_path = $image['image']->store('public/product_image');
                        if( !empty($image['default']) && ($image['default'] == 1 || $key=0) ){
                            $imgPath = $productImges->image_path; 
                        }
                    }
                    if( $save ){
                        $productImges->save();
                    }
                }
                $product = Product::findOrFail($productId);
                $product->product_image = $imgPath;
                $product->save();
            // }
        }
    }


    private function saveCategories($request, $productId){
        // dd($request->all() );
        // dd([$productId, $request->all() ]);
        // categories
        if( !empty($request->categories) && is_array((array)$request->categories) ){
            $productCategories = [];
            $productToCategory = new ProductToCategory;
            $productToCategory->deteleCategories($productId);
            foreach( (array) $request->categories as $category ){
                $productCategories[] = ['product_id'=>$productId,'category_id'=>$category];
            }
            if(!empty($productCategories)){
                ProductToCategory::insert($productCategories);
            }
        }
        return;
    }


    private function saveCollections($request, $productId){
        // dd($request->all() );
        // dd([$productId, $request->all() ]);
        // categories
        if( !empty($request->categories) && is_array((array)$request->categories) ){
            $productCollection = [];
            $productToCollection = new ProductToCollections;
            $productToCollection->deteleCollections($productId);
            foreach( (array) $request->collections as $collection ){
                $productCollection[] = ['product_id'=>$productId, 'collection_id'=>$collection];
            }
            // dd($productCollection[]);
            if(!empty($productCollection)){
                ProductToCollections::insert($productCollection);
            }
        }
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    private function saveDocs($request, $productId){
        // save the uploaded image 
        if(!empty($request->product_doc)){

            // if(!empty($product->product_image)){
            //     Storage::delete($product->product_image);
            // }
            
            $productImges = [];
            // dump($request->product_image);
            $save = false;
            foreach( $request->product_doc as $key => $image ){
                if( !empty( $image['image'] ) || !empty($image['id']) ){
                    $save = true;
                }

                // dd($image);
                if(!empty($image['id'])){
                    $productImges = ProductImage::findOrFail($image['id']);
                    // dd($productImges);
                }else{
                    $productImges = new ProductImage;
                }

                $productImges->product_id = $productId;
                $productImges->image_title = $image['title'];
                $productImges->doc_type ='doc';
                if( !empty( $image['image'] ) ){
                    $productImges->image_path = $image['image']->store('public/product_docs');
                    $productImges->file_name = $image['image']->getClientOriginalName();
                }

                if( $save ){
                    $productImges->save();
                }
            }
        }
    }

}
