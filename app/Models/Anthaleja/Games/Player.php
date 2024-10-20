<?php

namespace App\Models\Anthaleja\Games;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name', // Player's name or identifier
        'type', // 'human' or 'AI'
        'character_id', // Nullable for AI players
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
