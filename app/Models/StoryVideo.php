<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryVideo extends Model
{
    protected $fillable = ['title', 'video_path', 'is_active'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'story_video_product');
    }
}
