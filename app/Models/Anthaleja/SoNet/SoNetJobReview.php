<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetJobReview extends Model
{
    /**
     * Modello per le recensioni di lavoro su SoNet.
     *
     * Campi assegnabili in massa:
     * - job_offer_id: ID dell'offerta di lavoro a cui si riferisce la recensione.
     * - reviewer_id: ID del personaggio che ha scritto la recensione.
     * - reviewed_id: ID del personaggio recensito (es. datore di lavoro o lavoratore).
     * - rating: Valutazione numerica (ad esempio da 1 a 5).
     * - review: Testo della recensione.
     */
    protected $fillable = [
        'job_offer_id',
        'reviewer_id',
        'reviewed_id',
        'rating',
        'review'
    ];

    /**
     * Relazione con l'offerta di lavoro (jobOffer) recensita.
     * Questa funzione definisce una relazione "belongsTo" con il modello JobOffer.
     */
    public function jobOffer()
    {
        return $this->belongsTo(SoNetJobOffer::class);
    }

    /**
     * Relazione con il personaggio che ha scritto la recensione.
     * Definisce una relazione "belongsTo" con il modello Character, usando 'reviewer_id'.
     */
    public function reviewer()
    {
        return $this->belongsTo(Character::class, 'reviewer_id');
    }

    /**
     * Relazione con il personaggio che è stato recensito.
     * Definisce una relazione "belongsTo" con il modello Character, usando 'reviewed_id'.
     */
    public function reviewed()
    {
        return $this->belongsTo(Character::class, 'reviewed_id');
    }
}
