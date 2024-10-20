<?php

namespace App\Models\Anthaleja\Wiki;

use Illuminate\Database\Eloquent\Model;

class WikiRedirect extends Model
{
    protected $fillable = [
        'old_slug',
        'new_slug',
        'type',
        'redirect_count'
    ];

    public static function getRedirectByOldSlug($oldSlug)
    {
        return self::where('old_slug', $oldSlug)->first();
    }
}
