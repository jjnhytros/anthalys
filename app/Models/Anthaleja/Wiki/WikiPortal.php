<?php

namespace App\Models\Anthaleja\Wiki;

use Illuminate\Database\Eloquent\Model;

class WikiPortal extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cover_image'
    ];

    public function articles()
    {
        return $this->belongsToMany(WikiArticle::class, 'wiki_article_portal');
    }
}
