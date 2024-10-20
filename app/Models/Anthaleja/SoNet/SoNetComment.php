<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetComment extends Model
{
    // Campi assegnabili in massa
    protected $fillable = [
        'sonet_post_id',
        'parent_id',
        'character_id',
        'visibility',
        'content',
    ];

    /**
     * Relazione con il personaggio (character) che ha scritto il commento.
     * Un commento appartiene a un solo personaggio.
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Relazione con il post (SonetPost) a cui appartiene il commento.
     * Un commento appartiene a un solo post.
     */
    public function post()
    {
        return $this->belongsTo(SonetPost::class, 'sonet_post_id');
    }

    /**
     * Relazione con i commenti figli (replies).
     * Un commento può avere molti commenti figli.
     */
    public function children()
    {
        return $this->hasMany(SonetComment::class, 'parent_id');
    }

    /**
     * Relazione con il commento genitore.
     * Un commento può appartenere a un solo commento genitore.
     */
    public function parent()
    {
        return $this->belongsTo(SonetComment::class, 'parent_id');
    }

    /**
     * Relazione per ottenere le risposte a un commento.
     * Le risposte vengono ordinate in ordine decrescente (dal più recente).
     */
    public function replies()
    {
        return $this->hasMany(SonetComment::class, 'parent_id')->latest();
    }

    public function lumina()
    {
        return $this->morphMany(SoNetLuminum::class, 'luminable');
    }
}
