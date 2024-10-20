<?php

namespace App\Models\Anthaleja\Wiki;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class CharacterActivity extends Model
{
    protected $fillable = ['character_id', 'article_id', 'action'];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function article()
    {
        return $this->belongsTo(WikiArticle::class);
    }
}
