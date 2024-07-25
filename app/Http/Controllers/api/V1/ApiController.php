<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;


class ApiController extends Controller
{
    //

    /**
     * send the response 
     * 
     **/
    public function sendResponse($result, $message, $totalRows='', $metaData = '', $keywords='')
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        if(!empty($metaData)){
            $response['metaData']=$metaData;
        }
        if(!empty($keywords)){
            $response['keywords']=$keywords;
        }
        if(!empty($totalRows)){
            $response['totalRows']=$totalRows;
        }
        return response()->json($response);
    }

    /**
     * send the response 
     * 
     **/
    public function sendError($message, $code = 404, $errorMsg=[])
    {
        $response = [
            'success' => false,
            'message'=>$message,
            'errors'=>$errorMsg,
            'data' => [],
        ];
        return response()->json($response, $code);
    }

}
