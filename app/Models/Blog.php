<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function comments(){
        return $this->hasMany(BlogComment::class);
    }


    protected $casts = [
        'status' => 'integer'
    ];
}
