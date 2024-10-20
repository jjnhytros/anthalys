<?php

namespace App\Models\Anthaleja\Wiki;

use Illuminate\Database\Eloquent\Model;

class WikiTemplate extends Model
{
    protected $fillable = ['name', 'content'];

    public static function getByName($name)
    {
        return self::where('name', $name)->first();
    }
}
