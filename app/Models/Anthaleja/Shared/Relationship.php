<?php

namespace App\Models\Anthaleja;

use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\Character\Character;

class Relationship extends Model
{
    public $table = 'relationships';
    protected $fillable = [
        'character_id',
        'related_character_id',
        'relationship_name_id',
    ];

    protected static $overrideRules = [
        'divorce' => [
            'required_existing' => 'spouse',
            'override' => 'spouse',
        ],
        'adopt' => [
            'required_existing' => 'biological_parent',
            'override' => 'biological_parent',
        ],
        'step_parent' => [
            'required_existing' => 'parent',
            'override' => 'parent',
        ],
        'ex_partner' => [
            'required_existing' => 'partner',
            'override' => 'partner',
        ],
        'estranged' => [
            'required_existing' => 'sibling',
            'override' => 'sibling',
        ],
        'widowed' => [
            'required_existing' => 'spouse',
            'override' => 'spouse',
        ],
        'step_sibling' => [
            'required_existing' => 'sibling',
            'override' => 'sibling',
        ],
    ];


    // Relationship with Character
    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function relatedCharacter()
    {
        return $this->belongsTo(Character::class, 'related_character_id');
    }

    // Relationship with RelationshipName
    public function relationshipName()
    {
        return $this->belongsTo(RelationshipName::class);
    }

    // Validation method for adding a new relationship
    public static function canAddRelationship($characterId, $relatedCharacterId, $relationshipNameId)
    {
        // Retrieve existing relationships
        $existingRelationships = self::where('character_id', $characterId)
            ->where('related_character_id', $relatedCharacterId)
            ->pluck('relationship_name_id')
            ->toArray();

        // Define mutually exclusive relationship rules
        $mutuallyExclusive = [
            'spouse' => ['sibling', 'parent', 'child'],
            'sibling' => ['spouse', 'parent', 'child'],
            'parent' => ['spouse', 'child'],
            'child' => ['spouse', 'parent'],
        ];

        // Check if the new relationship type conflicts with any existing ones
        $relationshipName = RelationshipName::find($relationshipNameId)->name;

        if (isset($mutuallyExclusive[$relationshipName])) {
            foreach ($existingRelationships as $existingRelationshipId) {
                $existingRelationshipName = RelationshipName::find($existingRelationshipId)->name;
                if (in_array($existingRelationshipName, $mutuallyExclusive[$relationshipName])) {
                    return false; // Conflict found, cannot add relationship
                }
            }
        }

        return true; // No conflicts found, relationship can be added
    }

    public static function handleOverride(Relationship $relationship)
    {
        $relationshipName = $relationship->relationshipName;

        if ($relationshipName->required_existing) {
            // Check if the required relationship exists
            $existingRelationship = Relationship::where('character_id', $relationship->character_id)
                ->where('related_character_id', $relationship->related_character_id)
                ->whereHas('relationshipName', function ($query) use ($relationshipName) {
                    $query->where('name', $relationshipName->required_existing);
                })->first();

            if (!$existingRelationship) {
                throw new \Exception("The required relationship '{$relationshipName->required_existing}' does not exist.");
            }
        }

        if ($relationshipName->override) {
            // Find the existing relationship to override and delete it
            $relationshipToOverride = Relationship::where('character_id', $relationship->character_id)
                ->where('related_character_id', $relationship->related_character_id)
                ->whereHas('relationshipName', function ($query) use ($relationshipName) {
                    $query->where('name', $relationshipName->override);
                })->first();

            if ($relationshipToOverride) {
                $relationshipToOverride->delete();
            }
        }

        // Save the new relationship
        $relationship->save();
    }
}
