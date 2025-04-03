<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPromosi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'banner_promosis';  // Change to match Laravel's pluralization

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'image_url',
        'link',
        'price'
    ];
}

