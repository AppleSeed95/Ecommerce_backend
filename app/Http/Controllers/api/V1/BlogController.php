<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
use DB;

use App\Models\Blog;
use App\Models\Setting;

class BlogController extends ApiController
{

    //
    public function listBlogs(Request $request){
        $query = Blog::select()->where(['is_deleted'=>0]);

        return DataTables::of($query)
        ->editColumn('image', function($row){
            return Storage::url($row->image);
        })
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= '<a href="'.route('blogFrm').'/'.$row->id.'" title="Edit Product" class="btn btn-primary btn-xs mx-1"><i class="fas fa-pen"></i></a>';
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyBlog')) .")' title='Delete Menu' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
            // ->orderColumns(['product.display_name', 'product.modified_at'], 'desc')
        ->rawColumns(['action', 'image'])
        ->make(true);
    }

    //
    public function latestBlogs(Request $request){
        $recordsPerPage = 1;
        $page = $request->get('page') ?? 1;
        $query = Blog::select('*')->where(['is_deleted'=>0, 'is_deleted'=>0]);
        $totalRows = Blog::select('id')->where(['is_deleted'=>0, 'is_deleted'=>0])->get()->count();
        $blog = new Blog;
        $tagList = $blog->getTagList();
        $result = $query->forPage($page, $recordsPerPage)->get();

        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');

        foreach($result as $key=>$row){
            if(!empty($row->image)){
                $result[$key]->image = asset(Storage::url($row->image));
                $result[$key]->description = implode( ' ' ,array_slice(explode(' ', strip_tags($row->description)), 0, 80) ).'...';
            }else{
                $result[$key]->image = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
            if( !empty($row->tags)){
                $tags = explode(',', $row->tags);
                foreach($tags as $tag){
                    $tagsArr[] = '<a href="blog/tagged/'.Str::slug($tag).'" class="italic underline" >'.trim($tag).'</a>';
                }
                $result[$key]->tags = implode(', ', $tagsArr);
            }
        }
        return $this->sendResponse(['blog'=>$result, 'total'=>$totalRows, 'tags'=>$tagList], 'Blogs');
    }

    public function homepageListing(Request $request){
        $query = Blog::select()->where(['status'=> 1, 'is_deleted'=>0])->limit(6)->get();
        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');
        foreach($query as $key=>$row){
            if(!empty($row->image)){
                $query[$key]->image = asset(Storage::url($row->image));
            }else{
                $query[$key]->image = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
            
        }
        return $this->sendResponse($query, 'category');
    }

    public function getBlog(Request $request, $slug){
        $query = Blog::select()->where(['status'=> 1, 'is_deleted'=>0])->where('blog_slug', 'LIKE', $slug)->get();
        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');
        foreach($query as $key=>$row){
            if(!empty($row->image)){
                $query[$key]->image = asset(Storage::url($row->image));
            }else{
                $query[$key]->image = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
        }
        return $this->sendResponse($query, 'category');
    }
    

    //
    public function getTagBlogs(Request $request, $slug=''){
        $recordsPerPage = 12;
        // dump($slug);
        $page = (int)$request->get('page') ?? 1;
        // dd($page);
        $blog = new Blog;
        $query = Blog::select('*')->where(['status'=>1, 'is_deleted'=>0]);
        $query->whereRaw('LOWER(tags) LIKE "%'.str_replace('-', ' ', $slug).'%"');
        $totalRows = Blog::select('id')->where(['status'=>1, 'is_deleted'=>0])->whereRaw('LOWER(tags) LIKE "%'.str_replace('-', ' ', $slug).'%"')->get()->count();
        $tagList = $blog->getTagList();
        $result = $query->forPage($page, $recordsPerPage)->get();
        
        $setting = new Setting;
        $defultImg = $setting->getConfig('site_default_image');

        foreach($result as $key=>$row){
            if(!empty($row->image)){
                $result[$key]->image = asset(Storage::url($row->image));
                $result[$key]->description = implode( ' ' ,array_slice(explode(' ', strip_tags($row->description)), 0, 80) ).'...';
            }else{
                $result[$key]->image = asset(($defultImg)?Storage::url($defultImg):'img/image.png');
            }
            if( !empty($row->tags)){
                $tags = explode(',', $row->tags);
                foreach($tags as $tag){
                    $tagsArr[] = '<a href="/blogs/blog/tagged/'.Str::slug($tag).'" class="italic underline" >'.trim($tag).'</a>';
                }
                $result[$key]->tags = implode(', ', $tagsArr);
            }
        }
        return $this->sendResponse(['blog'=>$result, 'total'=>$totalRows, 'tags'=>$tagList], 'Blogs');
    }

    public function getBlogTags(Request $request){
        $blog = new Blog;
        $tagList = $blog->getTagList();
        return $this->sendResponse($tagList, 'Blog Tags');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = Blog::findOrFail($request->menuId);
        $record->is_deleted = 1;
        if($response = $record->save()){
            $response = $this->sendResponse($record, 'Selected blog is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }
}
