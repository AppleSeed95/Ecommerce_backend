<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    public $banners = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'banner_name',
        'banner_image',
        'banner_html',
        'banner_group',
        'banner_link',
        'status',
        'is_deleted',
        'created_by',
        'update_by',
        'banner_type',
    ];

    public function getBannerList(){
        $banners = self::select(
            'id',
            'banner_name',
            'banner_image',
            'banner_html',
            'banner_group',
            'banner_link',
            'status',
            'is_deleted',
            'created_by',
            'update_by'
        )
        ->where(['is_deleted'=>0, 'status'=>1])
        ->get();
        return $banners;
    }


}
