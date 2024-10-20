<?php

namespace App\Models\Anthaleja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes; // Abilita Soft Deletes

    // I campi che possono essere riempiti in modo massivo
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'message',
        'attachments',
        'is_message',
        'is_notification',
        'is_email',
        'is_archived',
        'status',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_message' => 'boolean',
        'is_notification' => 'boolean',
        'is_email' => 'boolean',
        'is_archived' => 'boolean',
        'status' => 'string',
    ];

    const STATUS_SENT = 'sent';
    const STATUS_UNREAD = 'unread';
    const STATUS_READ = 'read';
    const STATUS_ARCHIVED = 'archived';

    // Relazione con il mittente
    public function sender()
    {
        return $this->belongsTo(Character::class, 'sender_id');
    }

    // Relazione con il destinatario
    public function recipient()
    {
        return $this->belongsTo(Character::class, 'recipient_id');
    }

    public function sendMessage($recipientId, $subject, $messageContent)
    {
        try {
            $this->create([
                'sender_id' => $this->sender_id, // Assicurati che sender_id sia impostato
                'recipient_id' => $recipientId,
                'subject' => $subject,
                'message' => $messageContent,
                'is_message' => true,
                'is_notification' => false,

                'status' => 'sent', // Imposta lo stato come 'inviato'
            ]);
        } catch (\Exception $e) {
            // Notifica di errore durante l'invio
            dd('Error sending message: ' . $e->getMessage());
        }
    }

    public static function sendNotification($recipientId, $subject, $message, $url = null)
    {
        return self::create([
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'message' => $message,
            'url' => $url,
            'is_message' => false,
            'is_notification' => true,
            'status' => 'unread',
        ]);
    }
}
