<?php

namespace App\Models\Anthaleja\Marketplace;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['character_id', 'product_id', 'quantity', 'total_price', 'status'];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
