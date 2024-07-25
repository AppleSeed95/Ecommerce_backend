<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductToCollections extends Model
{
    use HasFactory;
    protected $table = 'product_to_collections';

    protected $fillable = ['product_id', 'collection_id'];



    public function product(){
        return $this->belongsTo(Product::class, 'foreign_key', 'product_id');
    }


    public function deteleCollections($productId){
        return ProductToCollections::where( [ 'product_id' => $productId ] )->delete();
    }


    public function products(){
        return $this->belongsTo(Product::class, 'foreign_key', 'product_id');
    }
    
    public function collection(){
        return $this->belongsTo(Collection::class, 'foreign_key', 'collection_id');
    }

    public function  collectionProductCount(){
        
    }
}
