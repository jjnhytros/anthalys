<?php

namespace App\Models\Anthaleja\Wiki;

use Illuminate\Database\Eloquent\Model;

class WikiCategory extends Model
{
    public $table = 'wiki_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id'
    ];

    public function children()
    {
        return $this->hasMany(WikiCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(WikiCategory::class, 'parent_id');
    }

    public function articles()
    {
        return $this->belongsToMany(WikiArticle::class, 'wiki_article_category', 'category_id', 'article_id');
    }
}
