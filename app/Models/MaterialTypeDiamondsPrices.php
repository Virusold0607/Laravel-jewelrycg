<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialTypeDiamondsPrices extends Model
{
    
    protected $table = 'material_type_diamonds_prices';
    protected $fillable = [
        'user_id', 'diamond_id', 'color', 'clarity', 'natural_price', 'lab_price',
    ];
    
    public function materialtypediamondscolor() {
        return $this->hasMany('App\Models\MaterialTypeDiamondsColor');
    }

    public function materialtypediamondsclarity() {
        return $this->hasMany('App\Models\MaterialTypeDiamondsClarity');
    }
}
