<?php

namespace App\Services;

use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\Relationship;

class SocialInteractionService
{
    public function interact(Character $characterOne, Character $characterTwo)
    {
        // Trova la relazione esistente tra i due personaggi
        $relationship = Relationship::where('character_id', $characterOne->id)
            ->where('related_character_id', $characterTwo->id)
            ->first();

        if (!$relationship) {
            return "Nessuna relazione trovata tra i due personaggi.";
        }

        // L'AI decide quale interazione è più appropriata in base alla relazione
        $interaction = $this->aiDecideInteraction($characterOne, $characterTwo, $relationship);

        // Esegui l'interazione
        return $this->handleInteraction($interaction, $relationship);
    }

    public function interactGroup(array $characters)
    {
        foreach ($characters as $characterOne) {
            foreach ($characters as $characterTwo) {
                if ($characterOne->id !== $characterTwo->id) {
                    $this->interact($characterOne, $characterTwo);  // Interazione tra tutti i membri
                }
            }
        }
        return "L'interazione di gruppo è avvenuta con successo.";
    }

    protected function aiDecideInteraction(Character $characterOne, Character $characterTwo, Relationship $relationship)
    {
        // Determina il tipo di relazione
        $relationshipType = $relationship->relationshipName->name;

        // In base al tipo di relazione, l'AI può decidere l'interazione
        switch ($relationshipType) {
            case 'spouse':
            case 'partner':
                return $this->chooseInteractionForCloseRelationship($relationship);
            case 'sibling':
            case 'parent':
                return $this->chooseInteractionForFamilyRelationship($relationship);
            case 'friend':
            case 'colleague':
                return $this->chooseInteractionForFriendlyRelationship($relationship);
            default:
                return 'neutral_interaction';
        }
    }

    protected function chooseInteractionForCloseRelationship(Relationship $relationship)
    {
        // Introduciamo interazioni come "scambio di regali" o "discussione"
        return rand(0, 3) ? 'friendly_chat' : (rand(0, 1) ? 'gift_exchange' : 'argument');
    }

    protected function chooseInteractionForFamilyRelationship(Relationship $relationship)
    {
        // Interazioni tra membri della famiglia
        return rand(0, 3) ? 'family_discussion' : (rand(0, 1) ? 'family_support' : 'family_argument');
    }

    protected function chooseInteractionForFriendlyRelationship(Relationship $relationship)
    {
        // Introduciamo una "collaborazione lavorativa" come interazione tra amici o colleghi
        return rand(0, 3) ? 'friendly_chat' : (rand(0, 1) ? 'work_collaboration' : 'work_disagreement');
    }

    protected function handleInteraction($interaction, Relationship $relationship)
    {
        // Gestione delle nuove interazioni
        switch ($interaction) {
            case 'friendly_chat':
                $relationship->relationship_date = now();
                $relationship->long_term_effect = json_encode(['positive' => true]);
                $relationship->save();
                return "I personaggi hanno avuto una conversazione amichevole.";
            case 'argument':
                $relationship->status = 'inactive';
                $relationship->long_term_effect = json_encode(['negative' => true]);
                $relationship->save();
                return "I personaggi hanno avuto un litigio.";
            case 'gift_exchange':
                $relationship->relationship_date = now();
                $relationship->long_term_effect = json_encode(['positive' => true, 'gift' => true]);
                $relationship->save();
                return "I personaggi hanno scambiato dei regali.";
            case 'family_support':
                $relationship->relationship_date = now();
                $relationship->save();
                return "Un membro della famiglia ha aiutato l'altro.";
            case 'work_collaboration':
                $relationship->relationship_date = now();
                $relationship->save();
                return "I personaggi hanno collaborato su un progetto lavorativo.";
            default:
                return "I personaggi hanno avuto un'interazione neutrale.";
        }
    }
}
