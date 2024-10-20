<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetConnection extends Model
{
    protected $table = 'sonet_connections';

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'status',
    ];

    // Relationship with the sender character
    public function sender()
    {
        return $this->belongsTo(Character::class, 'sender_id');
    }

    // Relationship with the recipient character
    public function recipient()
    {
        return $this->belongsTo(Character::class, 'recipient_id');
    }

    // Get the other character involved in the connection
    public function getOtherCharacter($character)
    {
        return $this->sender_id === $character->id ? $this->recipient : $this->sender;
    }
}
