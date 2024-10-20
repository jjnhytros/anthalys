<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoNetMessage extends Model
{
    use SoftDeletes;  // Soft delete per i messaggi

    // Specifica il nome della tabella associata al modello
    public $table = 'sonet_messages';

    // Definisce i campi che possono essere riempiti tramite inserimento massivo
    protected $fillable = [
        'chat_room_id',   // ID della chat room a cui appartiene il messaggio
        'sender_id',      // ID del personaggio che ha inviato il messaggio
        'message',        // Contenuto del messaggio
        'type',           // Tipo di messaggio: 'text', 'image', 'video', 'audio'
        'media_url',      // URL del file multimediale, se presente
        'edited',         // Indica se il messaggio è stato modificato
        'is_instance_message',
        'from_instance',
    ];

    /**
     * Relazione con ChatRoom.
     * Definisce che il messaggio appartiene a una chat room specifica.
     */
    public function chatRoom()
    {
        try {
            return $this->belongsTo(SoNetChatRoom::class);
        } catch (\Exception $e) {
            dd('Error retrieving chat room: ' . $e->getMessage());  // In caso di errore, mostra il messaggio di errore in inglese
        }
    }

    /**
     * Relazione con il mittente (Character).
     * Definisce una relazione "belongsTo" con il modello Character, utilizzando la chiave 'sender_id'.
     * Rappresenta il personaggio che ha inviato il messaggio.
     */
    public function sender()
    {
        try {
            return $this->belongsTo(Character::class, 'sender_id');
        } catch (\Exception $e) {
            dd('Error retrieving message sender: ' . $e->getMessage());
        }
    }

    /**
     * Relazione con il messaggio a cui si sta rispondendo (Message).
     * Definisce una relazione "belongsTo" con il modello Message per rappresentare una risposta.
     * Utilizza la chiave 'reply_to' per indicare il messaggio originale.
     */
    public function replyTo()
    {
        try {
            return $this->belongsTo(SonetMessage::class, 'reply_to');
        } catch (\Exception $e) {
            dd('Error retrieving reply message: ' . $e->getMessage());
        }
    }

    // Relazione "hasMany" per gestire le risposte a un messaggio
    public function replies()
    {
        return $this->hasMany(SonetMessage::class, 'reply_to');
    }
}
