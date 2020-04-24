<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    public function products(){
        return $this->hasMany(CampaignProduct::class);
    }

    protected $casts = [
        'status' => 'integer'
    ];
}
