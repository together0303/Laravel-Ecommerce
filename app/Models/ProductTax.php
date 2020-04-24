<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTax extends Model
{
    use HasFactory;

    public function products(){
        return $this->hasMany(Product::class,'tax_id');
    }

    protected $casts = [
        'price' => 'integer',
        'status' => 'integer'
    ];
}
