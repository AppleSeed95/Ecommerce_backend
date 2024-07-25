<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Banner;
use DB;

class BannerController extends ApiController
{
    //
    public function listBanner(Request $request){
        
        $query = Banner::select(
            'banners.id',
            'banners.banner_name',
            'banners.banner_image',
            'banners.banner_html',
            'banners.banner_group',
            'banners.banner_link',
            'banners.banner_type',
            'banners.sequence',
            'banners.status',
            'banners.is_deleted',
            'banners.created_at',
            'banners.updated_at',
            'cteated.name AS created_by',
            'updated.name AS update_by'
        )
        ->leftJoin('users AS cteated', 'cteated.id', '=', 'banners.created_by')
        ->leftJoin('users AS updated', 'updated.id', '=', 'banners.update_by')
        ->where(['banners.is_deleted'=>0]);

        return DataTables::of($query)
        ->editColumn('banner_image', function($row){
            if(!empty($row->banner_image)){
                return asset(Storage::url($row->banner_image))  ;
            }else{
                return '';
            }
        })
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= '<a href="'.route('bannerFrm', $row->id).'" title="Edit banner" class="btn btn-primary btn-xs mx-1"><i class="fas fa-pen"></i></a>';
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyBanner')) .")' title='Delete Banner' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        ->orderColumns(['banners.created_at', 'banners.updated_at'], 'desc')
        ->rawColumns(['action'])
        ->make(true);
    }


    public function getBanner(Request $request){
        DB::connection()->enableQueryLog();
        $query = Banner::select(
            'banners.id',
            'banners.banner_name',
            'banners.banner_image',
            'banners.banner_html',
            'banners.banner_group',
            'banners.banner_type',
            'banners.banner_link',
            'banners.sequence',
            'banners.status',
            'banners.is_deleted',
            'banners.created_at',
            'banners.updated_at',
            'cteated.name AS created_by',
            'updated.name AS update_by'
        )
        ->leftJoin('users AS cteated', 'cteated.id', '=', 'banners.created_by')
        ->leftJoin('users AS updated', 'updated.id', '=', 'banners.update_by')
        ->where(['banners.is_deleted'=>0, 'banners.status'=>1]);
        // pr($request->all());
        if(!empty($request->bannerGroup)){
            $query->where('banners.banner_group', $request->bannerGroup);
        }if(!empty($request->bannerType)){
            $query->where('banners.banner_type', $request->bannerType);
        }
        $query->orderBy('banners.sequence', 'ASC');
        $banners = $query->get()->toArray();
        $queries = DB::getQueryLog();
        foreach($banners as $k=>$banner){
            $banners[$k]['banner_image'] = asset(Storage::url($banner['banner_image']));
        }
        // $banners['sql'] = $queries;
        $response=[];
        // pr($banners);
        if(!empty($banners)){
            $response = $this->sendResponse($banners, 'Banners.');
        }else{
            $response = $this->sendError('No banner found', 200);
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = Banner::findOrFail($request->menuId);
        $record->is_deleted = 1;
        if($response = $record->save()){
            $response = $this->sendResponse($record, 'Selected banner is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }

    
}


    