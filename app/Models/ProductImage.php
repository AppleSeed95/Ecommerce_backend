<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image_path',
        'image_title',
        'is_default',
        'doc_type',
        'file_name',
        'status'
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'foreign_key', 'product_id');
    }
    
}
