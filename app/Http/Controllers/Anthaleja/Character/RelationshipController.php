<?php

namespace App\Http\Controllers\Anthaleja\Character;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Relationship;
use App\Models\Anthaleja\Character\Character;

class RelationshipController extends Controller
{
    public function index()
    {
        $character = Auth::user()->character;
        $relationships = $character->relationships()->with('relatedCharacter')->get();
        return view('anthaleja.sonet.relationships.index', compact('relationships'));
    }

    public function create()
    {
        $characters = Character::all();
        return view('anthaleja.sonet.relationships.create', compact('characters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'related_character_id' => 'required|integer|exists:characters,id',
            'relationship_type' => 'required|string',
        ]);

        $character = Auth::user()->character;

        Relationship::create([
            'character_id' => $character->id,
            'related_character_id' => $request->related_character_id,
            'relationship_type' => $request->relationship_type,
        ]);

        return redirect()->route('relationships.index')->with('success', 'Relationship added successfully.');
    }

    public function destroy(Relationship $relationship)
    {
        $relationship->delete();
        return redirect()->route('relationships.index')->with('success', 'Relationship deleted successfully.');
    }

    public function addRelationship(Request $request)
    {
        $request->validate([
            'character_id' => 'required|exists:characters,id',
            'related_character_id' => 'required|exists:characters,id|different:character_id',
            'relationship_name_id' => 'required|exists:relationship_names,id',
        ]);

        $characterId = $request->character_id;
        $relatedCharacterId = $request->related_character_id;
        $relationshipNameId = $request->relationship_name_id;

        Relationship::handleOverride($characterId, $relatedCharacterId, $relationshipNameId);

        if (!Relationship::canAddRelationship($characterId, $relatedCharacterId, $relationshipNameId)) {
            return response()->json(['error' => 'This relationship type is not allowed due to existing relationships.'], 400);
        }

        Relationship::create([
            'character_id' => $characterId,
            'related_character_id' => $relatedCharacterId,
            'relationship_name_id' => $relationshipNameId,
        ]);

        return response()->json(['success' => 'Relationship added successfully, with any necessary overrides applied.']);
    }
}
