<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public $categories = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
        'slug',
        'category_desc',
        'category_image',
        'meta_title',
        'meta_desc',
        'status',
        'is_deleted',
    ];

    public function getCategorysList(){
        $categories = self::select(
            'id',
            'category_name',
            'slug',
            'category_desc',
            'category_image',
            'meta_title',
            'meta_desc',
            'status',
            'is_deleted'
        )
        ->where('is_deleted',0)
        ->orderBy('updated_at', 'DESC')
        ->get();
        return $categories;
    }

    /**
     * 
     **/
    public function getSlug($str){
        $rawSlug = Str::slug($str);
        // pr($rawSlug);
        $sql = Category::select('id')->where('slug', 'LIKE', $rawSlug.'%')->get()->count();
        if($sql > 0){
            return $rawSlug.'-'.$sql;
        }else{
            return $rawSlug;
        }
    }

    public function getCategories(){
        return Self::where(['is_deleted'=>0])->orderBy('category_name', 'asc')->pluck('category_name', 'id')->toArray();
    }

    public function getCategoriesMenus(){
        return Self::where(['is_deleted'=>0])->orderBy('category_name', 'asc')->pluck('category_name', 'slug')->toArray();
    }

    public function search($search, $page=1, $rowsPerPage = 15){
        $query = Category::select('id', 'category_name as title', 'slug', 'category_image as image', 'meta_title', 'meta_desc', 'category_desc as desc', 'status')
            ->orWhere('category_name', 'LIKE', "%{$search}%")
            ->orWhere('meta_title', 'LIKE', "%{$search}%")
            ->orWhere('meta_desc', 'LIKE', "%{$search}%")
            ->orWhere('category_desc', 'LIKE', "%{$search}%")
            ->forPage($page, $rowsPerPage)
            ->get();
            // dd($query);
        $totalRows = $this->searchTotal($search);
        return ['rows'=>$query, 'total'=>$totalRows];
    }

    public function searchTotal($search){
        $result = Category::select()
            ->orWhere('category_name', 'LIKE', "%{$search}%")
            ->orWhere('meta_title', 'LIKE', "%{$search}%")
            ->orWhere('meta_desc', 'LIKE', "%{$search}%")
            ->orWhere('category_desc', 'LIKE', "%{$search}%")
            ->count();

        // print_r($result);
        return $result;
    }
}
