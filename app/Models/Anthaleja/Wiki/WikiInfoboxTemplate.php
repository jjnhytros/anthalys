<?php

namespace App\Models\Anthaleja\Wiki;

use App\Models\Anthaleja\City\Event;
use App\Models\Anthaleja\City\Location;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class WikiInfoboxTemplate extends Model
{
    protected $fillable = ['type', 'content', 'optional_fields'];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public static function getByType($type)
    {
        return self::where('type', $type)->first();
    }

    public function getOptionalFieldsAttribute()
    {
        return json_decode($this->attributes['optional_fields'], true) ?? [];
    }

    public function setOptionalFieldsAttribute($value)
    {
        $this->attributes['optional_fields'] = json_encode($value);
    }
}
