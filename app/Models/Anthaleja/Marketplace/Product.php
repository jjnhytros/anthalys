<?php

namespace App\Models\Anthaleja\Marketplace;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'quantity'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
