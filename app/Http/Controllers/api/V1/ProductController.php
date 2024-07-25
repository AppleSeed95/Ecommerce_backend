<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\ProductToCategory;

class ProductController extends ApiController
{
    //
    public function listProducts(Request $request){
        $query = Product::select()->where(['is_deleted'=>0]);

        return DataTables::of($query)
        ->editColumn('product_image', function($row){
            // return !empty($row->product_image)?Storage::url($row->product_image):asset('img/image.png');
            return (!empty($row->productImagesOnly[0]->image_path))?Storage::url($row->productImagesOnly[0]->image_path):asset('img/image.png');
        })
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= '<a href="'.route('productFrm').'/'.$row->id.'" title="Edit Product" class="btn btn-primary btn-xs mx-1"><i class="fas fa-pen"></i></a>';
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyProduct')) .")' title='Delete Menu' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        // ->orderColumns(['product.display_name', 'product.modified_at'], 'desc')
        ->rawColumns(['action', 'product_image'])
        ->make(true);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = Product::findOrFail($request->menuId);
        $record->is_deleted = 1;
        if($response = $record->save()){
            $response = $this->sendResponse($record, 'Selected menu is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }

    public function distroyImg(Request $request){

        $record = ProductImage::findOrFail($request->recordId);
        // $record->is_deleted = 1;
        if($response = $record->delete()){
            Storage::delete($record->product_image);
            $response = $this->sendResponse($record, 'Selected image is deleted.');
        }else{
            $response = $this->sendError('Unable to delete the product image', 203);
        }
        return $response;
    }

    public function getProducts(Request $request)
    {
        $catSlug = $request->category;
        DB::enableQueryLog();
        $where = ['is_deleted'=> 0,'status'=> 1];
        if(strtolower($catSlug) !='all'){
            $where['slug']=$catSlug;
        }

        $category = Category::select('id', 'category_name', 'slug')->where($where)->get()->toArray();
        // dump($category);
        $catIds = array_column($category, 'id');
        // dd($catIds);
        // $productsQuery = Product::select( 'products.id AS product_id', 'products.product_name', 'products.product_slug', 'products.meta_title', 'products.meta_desc', 'products.sku', 'products.vendor_name', 'products.product_desc', 'products.product_image', 'products.price', 'products.special_price', 'products.is_featured', 'products.availability', 'products.tags', 'products.status', 'products.is_deleted', 'products.modified_by','products.created_at', 'products.updated_at');
        $productsQuery = Product::select( '*');

        // collection Filter
        if( !empty($request->collections ) ){
            // $productsQuery->select('collections.id AS collections_id', 'collections.collection_name', 'collections.slug AS collections_slug', 'collections.collection_desc');
            $productsQuery->leftJoin('product_to_collections', 'product_to_collections.product_id', '=', 'products.id');
            $productsQuery->leftJoin('collections', 'collections.id', '=', 'product_to_collections.collection_id');
            $productsQuery->whereIn('collections.slug', $request->collections);
        }

        if(!empty($request->search)){
            $searchKey = $request->search;
            $productsQuery->where(function($query) use ($searchKey) {
                $query->orWhere('products.product_name', 'LIKE', '%'.$searchKey.'%');
                $query->orWhere('products.product_desc', 'LIKE', '%'.$searchKey.'%');
                $query->orWhere('products.meta_title', 'LIKE', '%'.$searchKey.'%');
                $query->orWhere('products.meta_desc', 'LIKE', '%'.$searchKey.'%');
            });
        }

        // tags Filters 
        if( !empty($request->tags ) ){
            $tags = $request->tags;
            $productsQuery->where(function($query) use ($tags) {
                foreach($tags as $tag){
                    $query->orWhere('products.tags', 'LIKE', '%'.$tag.'%');
                }
            });
        }

        if(!empty($request->sortOrder)){
            switch ($request->sortOrder) {
                case 'z-a':
                    $productsQuery->orderBy('products.product_name','DESC');
                    break;
                case 'feature':
                    $productsQuery->orderBy('products.is_featured','DESC');
                    break;
                case 'availability':
                    $productsQuery->orderBy('products.availability','DESC');
                    break;
                
                default:
                    $productsQuery->orderBy('products.product_name','ASC');
                    break;
            }
        }

        $productsQuery->where(['products.is_deleted'=> 0, 'products.status'=>1]);
        if( !empty( $catIds ) && ( empty($request->collections ) && empty($request->tags ) ) ){
            $productsQuery->leftJoin('product_to_category', 'product_to_category.product_id', '=', 'products.id');
            $productsQuery->whereIn('product_to_category.category_id', (array)$catIds);
        }

        $allProduct = clone $productsQuery;

        if(!empty($request->pageLimit)){
            $productsQuery->limit($request->pageLimit);
        }
        $products = $productsQuery->get();
        $totalProds = $allProduct->count();
        $productsQuery->groupBy('products.id');
        // dump(DB::getQueryLog());
        foreach($products as $index => $cat){
            // dump($cat->productImages->toArray());

            $prodImages = $this->getDefaultProductImage($cat->productImages->toArray());

            if(!empty($prodImages)){
                $products[$index]['product_image'] = Storage::url($prodImages);
            }
        }
        return $this->sendResponse($products, 'products', $totalProds);
    }

    public function getDefaultProductImage($prodImages){
        if(!empty($prodImages)){
            foreach( $prodImages as $key => $prodImage ){
                if(1 == $prodImage['is_default'] && !empty($prodImage['image_path'])){
                    return $prodImage['image_path'];
                }
            }
            return !empty($prodImages[0]['image_path'])?$prodImages[0]['image_path']:'/public/img/image-not-found-icon.svg';
        }
    }

    public function getProduct(Request $request)
    {
        $slug = $request->post('productSlug');
        DB::enableQueryLog();
        
        $products = Product::select('*')
            ->leftJoin('product_to_category', 'product_to_category.product_id', '=', 'products.id')
            ->where(['products.is_deleted'=> 0, 'products.status'=>1,'product_slug'=>$slug])
            // ->whereIn('product_to_category.category_id', $catIds)
            ->get();  // ->toArray();
        dump(DB::getQueryLog());
        dump($products);
        
        foreach($products as $index => $product){
            // dd($product->productImages);
            $prodImages = $this->getDefaultProductImage($product->productImagesOnly->toArray());
            // get the default image of product
            if(!empty($prodImages)){
                $products[$index]->product_image = Storage::url($prodImages);
            }

            foreach($product->productImagesOnly as $ind => $images){
                // dump($cat->productImages->toArray());
                $products[$index]->productImagesOnly[$ind]->image_path = Storage::url($images->image_path);
            }
            
            foreach($product->productDocsOnly as $ind => $docs){
                // dump($cat->productImages->toArray());
                $products[$index]->productDocsOnly[$ind]->image_path = Storage::url($docs->image_path);
            }
        }

        return $this->sendResponse($products, 'product');

    }

    public function getProductTags(Request $request){
        $productTagsRaw = Product::select('tags')->where(['is_deleted'=>0, 'status'=>1])->where('tags', '<>', '')->get()->toArray();
        $productTags = [];
        if(!empty($productTagsRaw)){
            foreach($productTagsRaw as $tags){
                foreach(explode(',', $tags['tags']) as $tag){
                    // $productTags[] = ['name'=>trim($tag)];
                    $productTags[] = trim($tag);
                }
            }
        }
        $productTags = array_filter( $productTags );
        sort( $productTags );
        $tags = array_map(function($tag){
            // dd($tag);
            return ['name'=>$tag];
        }, $productTags);
        // dd([$tags, $productTags]);
        return $this->sendResponse($tags, 'product tags');
    }

    public function getFeturedProds(Request $request, $count=4){
        $where = ['is_deleted'=> 0,'status'=> 1, 'is_featured'=>1];
        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');
        $products = Product::select('*')->where($where)->limit($count)->get();
        foreach($products as $index => $cat){
            // dump($cat->productImages->toArray());

            $prodImages = $this->getDefaultProductImage($cat->productImages->toArray());

            if(!empty($prodImages)){
                $products[$index]['product_image'] = asset(Storage::url($prodImages));
            }else{
                $products[$index]['product_image'] = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
        }
        return $this->sendResponse($products, 'Featured Products');
    }

    public function getRecentProductsByCats(Request $request){
        // dd('asdfa');
        $setting = new Setting;
        DB::enableQueryLog();
        $catIds = json_decode($setting->getConfig('recent_prodct_cats'), 1);
        $defultImg = $setting->getConfig('site_default_image');
        // $prodCats = ProductToCategory::select()
        // ->leftJoin('categories', 'categories.id','=', 'product_to_category.category_id')
        // ->whereIn('product_to_category.category_id', (array)$catIds)
        // 
        // ->get()
        // ->groupBy('category_id')
        $prodCats = Category::select()->where(['status'=>1, 'is_deleted'=>0])
        ->whereIn('id', $catIds)->orderBy('categories.category_name', 'ASC')->get();
        // dd($prodCats->toArray());
        $products = [];
        if(!empty($prodCats)){
            foreach($prodCats as $k=>$cat){
                $product = Product::select('*')->where(['status'=>1, 'is_deleted'=>0])
                ->leftJoin('product_to_category', 'product_to_category.product_id', '=', 'products.id')
                ->where('product_to_category.category_id', '=', $cat->id)
                ->orderBy('updated_at', 'DESC')->limit(4)->get();
                // print_r(DB::getQueryLog());
                if(!empty($product)){
                    foreach($product as $index => $prod){
                        $prodImages = $this->getDefaultProductImage($prod->productImages->toArray());
                        $products[$k]['category'] = $cat->category_name;
                        $products[$k]['products'][$index] = $prod->toArray();

                        if(!empty($prodImages)){
                            $products[$k]['products'][$index]['product_image'] = asset(Storage::url($prodImages));
                        }else{
                            $products[$k]['products'][$index]['product_image'] = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
                        }
                    }
                }
            }
        }
        return $this->sendResponse($products, 'Homepage Recent Products');
    }

}
