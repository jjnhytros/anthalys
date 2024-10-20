<?php

namespace App\Models\Anthaleja\Character;

use Illuminate\Database\Eloquent\Model;

class NPCReputation extends Model
{
    public $table = 'npc_reputations';
    protected $fillable = [
        'npc_id',
        'reputation_score', // Punteggio complessivo di reputazione
        'tasks_completed', // Numero di task completati
        'interactions', // Numero di interazioni (positive o negative)
        'feedback_received', // Feedback da altri NPC o personaggi
    ];

    public function npc()
    {
        return $this->belongsTo(NPC::class, 'npc_id');
    }

    public function calculateReputation()
    {
        $taskWeight = 0.6; // Peso per i task completati
        $interactionWeight = 0.3; // Peso per le interazioni
        $feedbackWeight = 0.1; // Peso per il feedback ricevuto

        $this->reputation_score =
            ($this->tasks_completed * $taskWeight) +
            ($this->interactions * $interactionWeight) +
            ($this->feedback_received * $feedbackWeight);

        $this->save();
    }

    public function decreaseReputation($amount)
    {
        $this->reputation_score = max(0, $this->reputation_score - $amount); // Assicurati che non scenda sotto zero
        $this->save();
    }

    public function increaseReputation($amount)
    {
        $this->reputation_score += $amount;
        $this->save();
    }
}
