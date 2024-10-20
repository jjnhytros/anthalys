<?php

namespace App\Models\Anthaleja\Wiki;

use Illuminate\Database\Eloquent\Model;

class WikiTag extends Model
{
    protected $fillable = ['name'];

    public function articles()
    {
        return $this->belongsToMany(WikiArticle::class, 'wiki_article_tag');
    }
}
