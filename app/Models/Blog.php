<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_title',
        'blog_slug',
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
        $sql = Blog::select('id')->where('blog_slug', 'LIKE', $rawSlug.'%')->get()->count();
        if($sql > 0){
            return $rawSlug.'-'.$sql;
        }
        return $rawSlug;
    }

    public function getTagList(){
        $result = Blog::select('tags')->where(['status'=>1, 'is_deleted'=>0])->whereRaw('tags IS NOT NULL')->get()->toArray();
        $tags=[];
        // dump($result);
        foreach($result as $tag){
            // dd($tag['tags']);
            $rawTag = explode(',', $tag['tags']);
            $tags = array_merge($tags, $rawTag);
        }

        $tags = array_unique(array_filter($tags));

        $finalTags = [];

        foreach( $tags as $tag ){
            $finalTags[Str::slug($tag)] = trim(ucwords(strtolower($tag)));
        }
        return $finalTags;
    }

    public function search($search, $page=1, $rowsPerPage = 15){
        $query = Blog::select('id', 'blog_title as title', 'blog_slug as slug', 'image', 'meta_title', 'meta_desc', 'description as desc', 'tags', 'status')
        ->orWhere(function($query) use($search){
            $query->orWhere('blog_title', 'LIKE', "%{$search}%")
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
        $result = Blog::select()
        ->orWhere(function($query) use($search){
            $query->orWhere('blog_title', 'LIKE', "%{$search}%")
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
