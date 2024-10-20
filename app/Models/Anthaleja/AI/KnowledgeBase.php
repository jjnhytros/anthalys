<?php

namespace App\Models\Anthaleja\AI;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Wiki\WikiArticle;
use App\Models\Anthaleja\Wiki\WikiCategory;

class KnowledgeBase extends Model
{
    protected $table = 'knowledge_base';

    protected $fillable = [
        'wiki_article_id', // Solo il riferimento all'articolo della Wiki
        'category_id',
        'tags',
        'type'
    ];

    // Relazione con gli articoli della Wiki
    public function article()
    {
        return $this->belongsTo(WikiArticle::class, 'wiki_article_id');
    }

    public function category()
    {
        return $this->belongsTo(WikiCategory::class);
    }
}
