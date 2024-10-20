<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class SoNetMention extends Model
{
    /**
     * Relazione con il SonetPost.
     *
     * Definisce una relazione "belongsTo" con il modello SonetPost.
     * Rappresenta il post in cui l'utente è stato menzionato.
     */
    public function sonetPost()
    {
        return $this->belongsTo(SonetPost::class);
    }

    /**
     * Relazione con il Commento (Comment).
     *
     * Definisce una relazione "belongsTo" con il modello Comment.
     * Rappresenta il commento in cui l'utente è stato menzionato.
     */
    public function comment()
    {
        return $this->belongsTo(SoNetComment::class);
    }

    /**
     * Relazione con il personaggio menzionato (Character).
     *
     * Definisce una relazione "belongsTo" con il modello Character, utilizzando la chiave 'mentioned_id'.
     * Indica il personaggio menzionato nel post o nel commento.
     */
    public function mentioned()
    {
        return $this->belongsTo(Character::class, 'mentioned_id');
    }
}
