<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metal extends Model
{
    protected $fillable = [
        'metal_name',
        'metal_purity',
        'slug',
    ];
}
