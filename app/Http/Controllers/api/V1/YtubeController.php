<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

use App\Models\YtubeVideo;

class YtubeController extends ApiController
{
    //
    public function save(Request $request){
        // pr($request->input());
        $msg='Youtube video saved succesfully!';
        $validationRules = [
            'video_title'=>'required|string|max:250',
            'video_link'=>'required|url',
            'video_start'=>'numeric',
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'video_title.required'=>'Youtube video title is required.',
            'video_title.max'=>'Youtube video title can not be greater than 250 chars.',
            'video_link.required'=>'Youtube video link is required.',
            'video_link.url'=>'Invalide video link is provided.',
            // 'video_start.max'=>'Youtube video title route can not exceeds than 75 chars.',
        ]);
        // dump($request->all());

        if( $validator->fails() ){
            return $this->sendError('Validation Error', 206, $validator->errors());
        }

        $video = new YtubeVideo;
        if( $request->has('id') && !empty($request->id)){
            $msg='Youtube video updated succesfully!';
            $video = YtubeVideo::findOrFail($request->id);
            // $video->updated_by = Auth::id();
            $video->id = $request->id;
        }
        
        $video->video_title = $request->video_title;
        $video->video_link = $request->video_link;
        $video->video_start = $request->video_start;
        $video->sort_order = $request->sort_order;
        $video->status = $request->status ?? 1;
        $video->is_deleted = $request->is_deleted ?? 0;

        if($response = $video->save()){
            $response = $this->sendResponse($video, $msg);
        }else{
            $response = $this->sendError($response);
        }
        return $response;
    }

    public function listVideo(Request $request){
        $query = YtubeVideo::select(
            'id',
            'video_title',
            'video_link',
            'video_start',
            'status',
            'created_at',
            'updated_at',
            'sort_order'
        )
        ->where(['is_deleted'=>0]);

        return DataTables::of($query)
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= "<button onClick='fillModelBoxFrmData(".$row.")' title='Edit Youtube Video' class='btn btn-primary btn-xs mx-1'><i class='fas fa-pen'></i></button>";
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyYTVideo')) .")' title='Youtube Video Menu' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        ->orderColumns(['updated_at','created_at'], 'desc')
        ->rawColumns(['action'])
        ->make(true);
    }

    // public function destroy(Request $request){
    //     $video = MeYtubeVideonu::findOrFail($request->menuId);
    //     $video->is_deleted = 1;
    //     if($response = $video->save()){
    //         $response = $this->sendResponse($video, 'Selected Youtube video is deleted.');
    //     }else{
    //         $response = $this->sendError($response, 203);
    //     }
    //     return $response;
    // }   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = YtubeVideo::findOrFail($request->menuId);
        $record->is_deleted = 1;
        if($response = $record->save()){
            $response = $this->sendResponse($record, 'Selected Youtube video is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }


    public function reactVideo(Request $request){
        $query = YtubeVideo::select(
            'id',
            'video_title',
            'video_link',
            'video_start',
            'status'
        )
        ->where(['is_deleted'=>0])
        ->orderBy('sort_order', 'ASC')
        ->get()->toArray();
        if($query){
            $response = $this->sendResponse($query, 'Selected menu is deleted.');
        }else{
            $response = $this->sendError('No Youtube video is found.', 203);
        }
        return $response;
    }

}
