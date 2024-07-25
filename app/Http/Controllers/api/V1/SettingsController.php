<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Setting;

class SettingsController extends ApiController
{
    //
    public function settings(){
        $setting = new Setting;
        $settings=$setting->settingList();
        $response = $this->sendResponse($settings, 'Site settings.');
        return $response;
    }

    //
    public function setting($key){
        $setting = new Setting;
        $settings = $setting->settingList($key);
        // $data = Setting::select()->where('status','=',1)->where('config_key', 'LIKE', $key)->get()->toArray();
        // $settings=[];
        // foreach($data as $arr){
        //     if( $arr['value_type'] == 'img' ){
        //         $settings[$arr['config_key']] = Storage::url($arr['config_value']);
        //     }else{
        //         $settings[$arr['config_key']] = $arr['config_value'];
        //     }
        // }
        $response = $this->sendResponse($settings, 'Site settings.');
        return $response;
    }

}
