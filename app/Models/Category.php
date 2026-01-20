<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'image',
        'banner_image',
        'name',
        'slug',
        'menu',
        'home_category',
        'footer',
    ];
}
