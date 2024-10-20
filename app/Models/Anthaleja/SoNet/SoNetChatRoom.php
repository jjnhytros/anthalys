<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class SoNetChatRoom extends Model
{
    // Definisce la tabella associata al modello
    public $table = 'sonet_chat_rooms';

    // Campi assegnabili in massa
    protected $fillable = [
        'name',       // Nome della chat room
        'type',       // Tipo di chat room (es. pubblica, privata)
        'created_by', // ID del creatore della chat room
    ];

    /**
     * Relazione con i messaggi inviati nella chat room.
     * Un messaggio ha un sender_id che rappresenta il personaggio che ha inviato il messaggio.
     */
    public function sonetMessages()
    {
        return $this->hasMany(SoNetMessage::class, 'sender_id');
    }

    /**
     * Relazione con il creatore della chat room.
     * Un creatore è un character che ha creato la chat room.
     */
    public function creator()
    {
        return $this->belongsTo(Character::class, 'created_by');
    }

    /**
     * Relazione con i partecipanti alla chat room.
     * Ogni chat room può avere molti partecipanti (characters).
     */
    public function participants()
    {
        return $this->belongsToMany(Character::class, 'sonet_chat_room_participants');
    }
}
