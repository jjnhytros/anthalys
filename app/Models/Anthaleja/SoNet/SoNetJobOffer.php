<?php

namespace App\Models\Anthaleja\SoNet;

use App\Models\Anthaleja\Character\Character;
use Illuminate\Database\Eloquent\Model;

class SoNetJobOffer extends Model
{
    /**
     * Modello per le offerte di lavoro su SoNet.
     *
     * Campi assegnabili in massa:
     * - character_id: ID del personaggio che ha creato l'offerta di lavoro.
     * - title: Titolo dell'offerta di lavoro.
     * - description: Descrizione del lavoro offerto.
     * - location: Località del lavoro.
     * - salary: Salario offerto.
     * - job_type: Tipo di lavoro (full-time, part-time, ecc.).
     * - required_skills: Competenze richieste per il lavoro.
     * - negotiable: Flag per indicare se lo stipendio è negoziabile (booleano).
     */
    protected $fillable = [
        'character_id',
        'title',
        'description',
        'location',
        'salary',
        'job_type',
        'required_skills',
        'negotiable'
    ];

    /**
     * Relazione con il personaggio (character) che ha creato l'offerta di lavoro.
     * Questa funzione definisce una relazione "belongsTo" con il modello Character.
     */
    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
