<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductToCategory extends Model
{
    use HasFactory;
    protected $table = 'product_to_category';

    protected $fillable = ['product_id', 'category_id'];



    public function product(){
        return $this->belongsTo(Product::class, 'foreign_key', 'product_id');
    }


    public function deteleCategories($productId){
        return ProductToCategory::where( [ 'product_id' => $productId ] )->delete();
    }

}
