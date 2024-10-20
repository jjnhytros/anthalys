<?php

namespace App\Models\Anthaleja\Wiki;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class WikiArticle extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'html_content',
        'published_at',
        'character_id',
        'category_id' // Aggiunto per chiarezza
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function tags()
    {
        return $this->belongsToMany(WikiTag::class, 'wiki_article_tag');
    }

    public function portals()
    {
        return $this->belongsToMany(WikiPortal::class, 'wiki_article_portal');
    }

    public function categories()
    {
        return $this->belongsToMany(WikiCategory::class, 'wiki_article_category', 'article_id', 'category_id');
    }
}
