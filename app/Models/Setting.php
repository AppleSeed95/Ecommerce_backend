<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';

    protected $fillable = ['config_group', 'config_key', 'config_value', 'value_type', 'status'];

    /**
     * 
     * 
     * */
    public function getConfigValue($key){
        $sql = Setting::select()->where('config_key', 'LIKE', $key)->get()->first();
        return $sql;
    }


    public function getConfig($key){
        $sql = Setting::select('config_value')->where('config_key', 'LIKE', $key)->first()->toArray();
        return $sql['config_value'];
    }

    public function getConfigs($keys){
        if( is_array($keys) ){
            $settings = [];
            foreach($keys as $key){
                $setting = $this->settingList($key);
                $settings[$key] = $setting[$key];
            }
            return $settings;
        }
    }


    public function settingList($key=''){
        $query = Setting::select()->where('status','=',1);
        if(!empty($key)){
            $query->where('config_key', 'LIKE', $key);
        }
        $data = $query->get()->toArray();
        $settings=[];
        foreach($data as $arr){
            if( $arr['value_type'] == 'img' ){
                $settings[$arr['config_key']] = asset(Storage::url($arr['config_value']));
            }elseif( $arr['value_type']  == 'imgJson'){
                $array = [];
                foreach(json_decode($arr['config_value']) as $img){
                    $array[] =  asset(Storage::url($img));
                }
                $settings[$arr['config_key']] = $array;
            }elseif($arr['value_type']  == 'json'){
                $settings[$arr['config_key']] = json_decode($arr['config_value'], 1);
            }else{
                $settings[$arr['config_key']] = $arr['config_value'];
            }
        }
        return $settings;
    }



}
