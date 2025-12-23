<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page_url',
        'meta_title',
        'meta_description',
        'extra_tags',
    ];
}
