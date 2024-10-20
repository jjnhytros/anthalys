<?php

namespace App\Models\Anthaleja\SoNet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class SoNetPost extends Model
{
    // Definizione della tabella associata al modello
    public $table = "sonet_posts";

    protected $fillable = [
        'character_id',
        'content',
        'media',
        'visibility',
        'publish_at',
        'expires_at',
        'warning_sent',
    ];

    /**
     * Relazione con il modello Character.
     * Indica che un post appartiene a un personaggio (autore del post).
     */
    public function character()
    {
        try {
            return $this->belongsTo(Character::class, 'character_id');
        } catch (\Exception $e) {
            dd('Error retrieving character: ' . $e->getMessage());
        }
    }

    /**
     * Relazione con il modello Comment.
     * Definisce la relazione di un post con i commenti associati.
     */
    public function comments()
    {
        try {
            return $this->hasMany(SonetComment::class, 'sonet_post_id');  // Relazione con i commenti del post
        } catch (\Exception $e) {
            dd('Error retrieving comments: ' . $e->getMessage());  // Traduzione dell'errore in inglese
        }
    }

    /**
     * Relazione con il modello Mention.
     * Indica che un post può avere diverse menzioni associate.
     */
    public function mentions()
    {
        try {
            return $this->hasMany(SoNetMention::class, 'sonet_post_id');  // Relazione con le menzioni nel post
        } catch (\Exception $e) {
            dd('Error retrieving mentions: ' . $e->getMessage());  // Traduzione dell'errore in inglese
        }
    }

    public function lumina()
    {
        return $this->morphMany(SoNetLuminum::class, 'luminable');
    }

    /**
     * Scope per filtrare i post visibili al personaggio corrente.
     */
    public function scopeVisibleTo($query, $character)
    {
        return $query->where(function ($q) use ($character) {
            $q->where('visibility', 'public')
                ->orWhere('visibility', 'follower')
                ->orWhere(function ($subQuery) use ($character) {
                    $subQuery->where('visibility', 'private')->where('character_id', $character->id);
                })
                ->orWhere(function ($subQuery) use ($character) {
                    $subQuery->where('visibility', 'mentioned')
                        ->whereHas('mentions', function ($mentionQuery) use ($character) {
                            $mentionQuery->where('mentioned_id', $character->id);
                        });
                });
        });
    }

    // Funzione per verificare se il post è scaduto
    public function isExpired()
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }

    // Funzione per inviare un avviso prima della scadenza
    public function sendExpirationWarning()
    {
        // Da sistemare impostandolo come messaggio e is_notification
        // // if ($this->expires_at && !$this->warning_sent) {
        // //     $daysLeft = now()->diffInDays($this->expires_at);

        // //     if ($daysLeft <= 7) { // Avviso quando mancano 7 giorni
        // //         $character = $this->character;
        // //         Notification::send($character, new PostExpirationWarningNotification($this));

        // //         $this->warning_sent = true;
        // //         $this->save();
        // //     }
        // }
    }
}
