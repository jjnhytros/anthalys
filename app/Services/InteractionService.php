<?php

namespace App\Services;

use App\Models\Anthaleja\Character\Character;

class InteractionService
{
    public function createInteraction(Character $character1, Character $character2)
    {
        // Determina il tipo di interazione (es. basata sulla reputazione)
        $interactionType = $this->determineInteractionType($character1, $character2);

        // Aggiorna gli attributi in base al tipo di interazione
        switch ($interactionType) {
            case 'positive':
                $this->handlePositiveInteraction($character1, $character2);
                break;
            case 'negative':
                $this->handleNegativeInteraction($character1, $character2);
                break;
            case 'neutral':
            default:
                // Interazione neutrale, nessun cambiamento
                break;
        }

        // Salva i cambiamenti
        $character1->save();
        $character2->save();
    }

    protected function determineInteractionType(Character $character1, Character $character2)
    {
        // Se la reputazione di entrambi è alta, interazione positiva
        if ($character1->reputation > 50 && $character2->reputation > 50) {
            return 'positive';
        }
        // Se uno dei due ha una reputazione bassa, interazione negativa
        elseif ($character1->reputation < 20 || $character2->reputation < 20) {
            return 'negative';
        }
        // Altrimenti, interazione neutrale
        return 'neutral';
    }

    protected function handlePositiveInteraction(Character $character1, Character $character2)
    {
        // Aumenta la lealtà e la reputazione di entrambi i personaggi
        $character1->loyalty += 5;
        $character2->loyalty += 5;

        $character1->reputation += 2;
        $character2->reputation += 2;
    }

    protected function handleNegativeInteraction(Character $character1, Character $character2)
    {
        // Riduci la lealtà e la reputazione di entrambi i personaggi
        $character1->loyalty -= 5;
        $character2->loyalty -= 5;

        $character1->reputation -= 2;
        $character2->reputation -= 2;
    }
}
