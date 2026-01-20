<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['name', 'slug', 'image', 'banner_image', 'is_active'];

    public static function generateUniqueSlug($slug, $id = null)
    {
        $originalSlug = $slug;
        $count = 1;

        $query = static::where('slug', $slug);
        if ($id) {
            $query->where('id', '!=', $id);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = static::where('slug', $slug);
            if ($id) {
                $query->where('id', '!=', $id);
            }
        }

        return $slug;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product');
    }
}
