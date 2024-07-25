<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

use App\Models\Category;

class CategoriesController extends ApiController
{
    //

    public function listCategory(Request $request){
        $query = Category::select()
        ->where('is_deleted', 0);

        return DataTables::of($query)
        ->editColumn('category_image', function($row){
            // pr($row);
            if(!empty($row->category_image)){
                // echo $row->category_image;
                // return Storage::url($row->category_image);
                // $file  =storage_path('app/' . $row->category_image);
                // $file  =storage_path($row->category_image);
                // return url('app/storage/' .$row->category_image);
                return Storage::url($row->category_image);
            }else{
                return '';
            }
        })
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= "<button onClick='fillModelBoxFrmData(".$row.")' title='Edit Category' class='btn btn-primary btn-xs mx-1'><i class='fas fa-pen'></i></button>";
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyCategory')) .")' title='Delete Banner' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        ->orderColumns(['created_at', 'updated_at'], 'desc')
        ->rawColumns(['action'])
        ->make(true);

    }

    public function store(Request $request){
        // pr([$request->input(), __METHOD__]);
        $msg='Menu saved succesfully!';
        $validationRules = [
            'category_name'=>'required|max:75',
            // 'collection_desc'=>'max:180',
            'meta_title'=>'max:200',
            'meta_desc'=>'max:345',
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'category_name.required'=>'Category name is required.',
            'category_name.max'=>'Category name can not be greater than 75 chars.',
            'meta_title.string'=>'Meta title should be string .',
            'meta_desc'=>'Meta description .',
            'meta_title.max'=>'Meta title can have 200 charactors.',
            'meta_desc.max'=>'Meta description can have 345 charactors.'
        ]);

        if( $validator->fails() ){
            return $this->sendError('Validation Error', 206, $validator->errors());
        }

        $category = new Category;
        if( $request->has('id') && !empty($request->id)){
            $msg='Category updated succesfully!';
            $category = Category::findOrFail($request->id);
            // $category->updated_by = Auth::id();
            $category->id = $request->id;
        }else{
            // category slug
            $category->slug = $category->getSlug( $category->category_name );
        }
        // $category->created_by = Auth::id();
        $category->category_name = $request->category_name;
        $category->category_desc = $request->collection_desc;
        $category->meta_title = $request->meta_title;
        $category->meta_desc = $request->meta_desc;
        $category->status = $request->status;
        
        // save the uploaded image 
        if(!empty($request->category_image) && $request->category_image != 'undefined'){
            if(!empty($category->category_image)){
                Storage::delete($category->category_image);
            }
            $category->category_image = $request->file('category_image')->store('public/category_image');
        }


        // SEO url / slug
        if( !empty( $request->slug )){
            $slug = $request->slug;
        }else{
            $slug = $request->category_name;
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
        $categories = Category::where('is_deleted', 0)->pluck('slug', 'category_name');
        $response = $this->sendResponse($categories, 'Banners.');
    }

    public function getCategoryDetails(Request $request, $slug){
        $where = ['is_deleted'=> 0,'status'=> 1];
        if(strtolower($slug) !='all'){
            $where['slug']=$slug;
        }
        // dd([$request->categoryName, $slug]);
        $category = Category::select('*')->where($where)->get()->toArray();
        foreach($category as $index => $cat){
            if(!empty($category[$index]['category_image'])){
                $category[$index]['category_image'] = asset(Storage::url($category[$index]['category_image']));
            }
        }
        // dd($category);
        return $this->sendResponse($category, 'category');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = Category::findOrFail($request->menuId);
        $record->is_deleted = 1;
        if($response = $record->save()){
            $response = $this->sendResponse($record, 'Selected record is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }

}
