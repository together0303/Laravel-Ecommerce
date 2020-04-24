<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function childCategories(){
        return $this->hasMany(ChildCategory::class,'sub_category_id');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    protected $casts = [
        'category_id' => 'integer',
        'status' => 'integer'
    ];
}
