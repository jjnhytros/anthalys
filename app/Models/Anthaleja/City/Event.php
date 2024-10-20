<?php

namespace App\Models\Anthaleja\City;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'location_id'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
