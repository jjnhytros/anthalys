<?php

namespace App\Models\Anthaleja\City;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'city', 'region', 'country', 'description'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
