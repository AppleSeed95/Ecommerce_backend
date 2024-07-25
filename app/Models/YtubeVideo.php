<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YtubeVideo extends Model
{
    use HasFactory;

    protected $table = 'ytube_videos';

    public $banners = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'video_title',
        'video_link',
        'video_start',
        'sort_order',
        'status',
        'is_deleted'
    ];



}
