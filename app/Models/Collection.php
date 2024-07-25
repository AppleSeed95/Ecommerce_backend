<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;

    protected $table = 'collections';

    public $collections = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array <int, string>
     */
    protected $fillable = [
        'collection_name',
        'slug',
        'collection_desc',
        'collection_image',
        'meta_title',
        'meta_desc',
        'status',
        'is_deleted',
    ];

    public function getCollectionsList(){
        $collections = self::select(
            'id',
            'collection_name',
            'slug',
            'collection_desc',
            'collection_image',
            'meta_title',
            'meta_desc',
            'status',
            'is_deleted'
        )
        ->where('is_deleted',0)
        ->orderBy('updated_at', 'DESC')
        ->get();
        return $collections;
    }

    /**
     * 
     **/
    public function getSlug($str){
        $rawSlug = Str::slug($str);
        // pr($rawSlug);
        $sql = Collection::select('id')->where('slug', 'LIKE', $rawSlug.'%')->get()->count();
        if($sql > 0){
            return $rawSlug.'-'.$sql;
        }else{
            return $rawSlug;
        }
    }

    public function getCollections(){
        return Self::where(['is_deleted'=>0])->orderBy('collection_name', 'asc')->pluck('collection_name', 'id')->toArray();
    }

    public function getCollectionsMenus(){
        return Self::where(['is_deleted'=>0])->orderBy('collection_name', 'asc')->pluck('collection_name', 'slug')->toArray();
    }

    // public function collectionProductCount(){
    //     return Self::where(['is_deleted'=>0])->orderBy('collection_name', 'asc')->pluck('collection_name', 'slug')->toArray();
    // }
    
    public function collectionToProducts(){
        return $this->hasMany(ProductToCollections::class);
    }

    // public function collectionToProductCount(){
    //     return $this->hasMany(ProductToCollections::class);
    // }

}
