<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

use App\Models\Collection;

class CollectionController extends ApiController
{
    //

    public function listCollection(Request $request){
        $query = Collection::select()
        ->where('is_deleted', 0);

        return DataTables::of($query)
        ->editColumn('collection_image', function($row){
            if(!empty($row->category_image)){
                return Storage::url($row->category_image);
            }else{
                return '';
            }
        })
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= "<button onClick='fillModelBoxFrmData(".$row.")' title='Edit Collection' class='btn btn-primary btn-xs mx-1'><i class='fas fa-pen'></i></button>";
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyCollection')) .")' title='Delete Banner' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        ->orderColumns(['created_at', 'updated_at'], 'desc')
        ->rawColumns(['action'])
        ->make(true);

    }


    public function store(Request $request){
        pr($request->input());
        $msg='Menu saved succesfully!';
        $validationRules = [
            'collection_name'=>'required|max:75',
            // 'collection_desc'=>'max:180',
            'meta_title'=>'max:200',
            'meta_desc'=>'max:345',
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'collection_name.required'=>'Collection name is required.',
            'collection_name.max'=>'Collection name can not be greater than 75 chars.',
            'meta_title.string'=>'Meta title should be string .',
            'meta_desc'=>'Meta description .',
            'meta_title.max'=>'Meta title can have 200 charactors.',
            'meta_desc.max'=>'Meta description can have 345 charactors.'
        ]);

        if( $validator->fails() ){
            return $this->sendError('Validation Error', 206, $validator->errors());
        }

        $category = new Collection;
        if( $request->has('id') && !empty($request->id)){
            $msg='Collection updated succesfully!';
            $category = Collection::findOrFail($request->id);
            // $category->updated_by = Auth::id();
            $category->id = $request->id;
        }else{
            // category slug
            $category->slug = $category->getSlug( $category->collection_name );
        }
        // $category->created_by = Auth::id();
        $category->collection_name = $request->collection_name;
        $category->collection_desc = $request->collection_desc;
        $category->meta_title = $request->meta_title;
        $category->meta_desc = $request->meta_desc;
        $category->status = $request->status;
        
        // save the uploaded image 
        if(!empty($request->collection_image) && $request->collection_image != 'undefined'){
            if(!empty($category->collection_image)){
                Storage::delete($category->collection_image);
            }
            $category->collection_image = $request->file('collection_image')->store('public/collection_image');
        }

        // SEO url / slug
        if( !empty( $request->slug )){
            $slug = $request->slug;
        }else{
            $slug = $request->collection_name;
        }

        if( empty( $request->id ) ){
            $slug = $category->getSlug($slug);
        }
        // dump($request->name);

        $category->slug = $slug ?? '';   
        // $category->sort_order = $request->sort_order ?? 0;
        // $category->expansion = 0;
        // $category->parent_id = $request->parent_id ?? 0;

        if($response = $category->save()){
            $response = $this->sendResponse($category, $msg);
        }else{
            $response = $this->sendError($response);
        }
        return $response;
    }

    public function categoryListCount(Request $request){
        $categories = Collection::where('is_deleted', 0)->pluck('slug', 'collection_name');
        $response = $this->sendResponse($categories, 'Collections');
    }

    public function getCollectionDetails(Request $request, $slug){
        $where = ['is_deleted'=> 0,'status'=> 1];
        if(strtolower($slug) !='all'){
            $where['slug']=$slug;
        }
        // dd([$request->categoryName, $slug]);
        $category = Collection::select('*')->where($where)->get()->toArray();
        foreach($category as $index => $cat){
            if(!empty($category[$index]['collection_image'])){
                $category[$index]['collection_image'] = Storage::url($category[$index]['collection_image']);
            }
        }
        // dd($category);
        return $this->sendResponse($category, 'collections');
    }

    public function getFilterCollection(Request $request){
        $categories = Collection::select('id','collection_name', 'slug')->withCount('collectionToProducts')->where('is_deleted', 0)->orderBy('collection_name', 'ASC')->get()->toArray();
        //dd($categories);
        // foreach($categories as $categories){
        //     dump($categorie->collectionToProducts());
        // }
        return $this->sendResponse($categories, 'Collections');
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = Collection::findOrFail($request->menuId);
        $record->is_deleted = 1;
        if($response = $record->save()){
            $response = $this->sendResponse($record, 'Selected menu is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }

}
