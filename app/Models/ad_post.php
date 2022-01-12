<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ad_post extends Model
{
    use HasFactory;

    function feature() {
        return $this->hasMany(Feature::class,'ad_post_id','id');
    }
    function image() {
        return $this->hasMany(image::class,'ad_post_id','id');
    }
}
