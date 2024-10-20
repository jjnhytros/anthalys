<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetFollower extends Model
{
    /**
     * Questo modello rappresenta la relazione di "follow" tra gli utenti in SoNet.
     * Sarà utilizzato per tracciare chi segue chi.
     *
     * Campi assegnabili in massa:
     * - follower_id: l'ID del personaggio che segue.
     * - following_id: l'ID del personaggio seguito.
     */
    protected $fillable = ['follower_id', 'following_id'];

    /**
     * Funzione per ottenere il personaggio che sta seguendo (follower).
     * Definisce una relazione "belongsTo" con il modello Character.
     */
    public function follower()
    {
        return $this->belongsTo(Character::class, 'follower_id');
    }

    /**
     * Funzione per ottenere il personaggio che viene seguito (following).
     * Definisce una relazione "belongsTo" con il modello Character.
     */
    public function following()
    {
        return $this->belongsTo(Character::class, 'following_id');
    }
}
