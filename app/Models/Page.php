<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_title',
        'page_slug',
        'image',
        'meta_title',
        'meta_desc',
        'description',
        'status',
        'is_deleted',
        'created_at',
        'updated_at',
        'tags'
    ];

    /**
     * 
     */
    public function getSlug($str){
        $rawSlug = Str::slug($str);
        // pr($rawSlug);
        $sql = Page::select('id')->where('page_slug', 'LIKE', $rawSlug.'%')->get()->count();
        if($sql > 0){
            return $rawSlug.'-'.$sql;
        }
        return $rawSlug;
    }

    public function getPageMenus(){
        return Self::where(['is_deleted'=>0, 'status' =>1])->orderBy('page_title', 'asc')->pluck('page_title', 'page_slug')->toArray();
    }

    public function search($search, $page=1, $rowsPerPage = 15){
        $query = Page::select('id', 'page_title as title', 'page_slug as slug', 'image', 'meta_title', 'meta_desc', 'description as desc', 'tags', 'status')
        ->orWhere(function($query) use($search){
            $query->orWhere('page_title', 'LIKE', "%{$search}%")
            ->orWhere('meta_title', 'LIKE', "%{$search}%")
            ->orWhere('meta_desc', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%")
            ->orWhere('tags', 'LIKE', "%{$search}%");
        })
        ->where(['status'=>'1', 'is_deleted'=>0])
            ->forPage($page, $rowsPerPage)
            ->get();
            // dd($query);
        $totalRows = $this->searchTotal($search);
        return ['rows'=>$query, 'total'=>$totalRows];
    }

    public function searchTotal($search){
        $result = Page::select()
        ->orWhere(function($query) use($search){
            $query->orWhere('page_title', 'LIKE', "%{$search}%")
            ->orWhere('meta_title', 'LIKE', "%{$search}%")
            ->orWhere('meta_desc', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%")
            ->orWhere('tags', 'LIKE', "%{$search}%");
        })
        ->where(['status'=>'1', 'is_deleted'=>0])->count();

        // print_r($result);
        return $result;
    }


}
