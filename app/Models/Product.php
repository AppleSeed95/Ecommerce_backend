<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class 
Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_name',
        'product_slug',
        'meta_title',
        'meta_desc',
        'sku',
        'vendor_name',
        'product_desc',
        'product_image',
        'price',
        'special_price',
        'status',
        'is_featured',
        'availability',
        'is_deleted',
        'modified_by',
        'created_at',
        'updated_at'
    ];

    /**
     * 
     */
    public function getSlug($str){
        $rawSlug = Str::slug($str);
        // pr($rawSlug);
        $sql = Product::select('id')->where('product_slug', 'LIKE', $rawSlug.'%')->get()->count();
        if($sql > 0){
            return $rawSlug.'-'.$sql;
        }
        return $rawSlug;
    }

    public function productImages(){
        return $this->hasMany(ProductImage::class);
    }

    public function productImagesOnly(){
        return $this->productImages()->where('doc_type','=', 'img');
    }

    public function productDocsOnly(){
        return $this->productImages()->where('doc_type','=', 'doc');
    }

    public function productToCategories(){
        return $this->hasMany(ProductToCategory::class);
    }

    public function ProductToCollections(){
        return $this->hasMany(ProductToCollections::class);
    }

    public function getProductMenus(){
        return Self::where(['is_deleted'=>0, 'status' =>1])->orderBy('product_name', 'asc')->pluck('product_name', 'product_slug')->toArray();
    }


    public function search($search, $page=1, $rowsPerPage = 15){
        $query = Product::select('id', 'product_name as title', 'product_slug as slug', 'meta_title', 'meta_desc', 'product_desc as desc', 'product_image as image', 'tags', 'status')
        ->orWhere(function($query) use ($search){
            $query->orWhere('product_name', 'LIKE', "%{$search}%")
            ->orWhere('meta_title', 'LIKE', "%{$search}%")
            ->orWhere('meta_desc', 'LIKE', "%{$search}%")
            ->orWhere('sku', 'LIKE', "%{$search}%")
            ->orWhere('vendor_name', 'LIKE', "%{$search}%")
            ->orWhere('product_desc', 'LIKE', "%{$search}%");
        })
        ->where(['status'=>1, 'is_deleted'=>0])
            ->forPage($page, $rowsPerPage)
            ->get();

        $totalRows = $this->searchTotal($search);
        return ['rows'=>$query, 'total'=>$totalRows];
    }

    public function searchTotal($search){
        $result = Product::select()
        ->orWhere(function($query) use ($search){
            $query->orWhere('product_name', 'LIKE', "%{$search}%")
            ->orWhere('meta_title', 'LIKE', "%{$search}%")
            ->orWhere('meta_desc', 'LIKE', "%{$search}%")
            ->orWhere('sku', 'LIKE', "%{$search}%")
            ->orWhere('vendor_name', 'LIKE', "%{$search}%")
            ->orWhere('product_desc', 'LIKE', "%{$search}%");
        })
        ->where(['status'=>1, 'is_deleted'=>0])->count();

        return $result;
    }


}
