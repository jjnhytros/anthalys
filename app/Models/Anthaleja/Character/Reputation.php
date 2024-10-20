<?php

namespace App\Models\Anthaleja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class Reputation extends Model
{
    protected $fillable = ['character_id', 'rated_character_id', 'rating', 'feedback'];

    /**
     * Relazione con il personaggio che ha dato la valutazione.
     */
    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }

    /**
     * Relazione con il personaggio che è stato valutato.
     */
    public function ratedCharacter()
    {
        return $this->belongsTo(Character::class, 'rated_character_id');
    }
}
