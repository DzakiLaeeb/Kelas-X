<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner_promosi';
    
    protected $fillable = [
        'title',
        'image_url',
        'link',
        'price'
    ];
}