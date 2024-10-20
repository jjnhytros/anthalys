<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\SonetRoom;

class SonetRoomController extends Controller
{
    public function index()
    {
        $rooms = SonetRoom::where('is_active', true)->get();
        return view('anthaleja.sonet.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('anthaleja.sonet.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:public,private,invite-only',
        ]);

        SonetRoom::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'created_by' => Auth::user()->character->id,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Stanza creata con successo.');
    }

    public function show(SonetRoom $room)
    {
        $room->load('messages.sender'); // Carica i messaggi e i rispettivi mittenti
        return view('anthaleja.sonet.rooms.show', compact('room'));
    }

    public function edit(SonetRoom $room)
    {
        return view('anthaleja.sonet.rooms.edit', compact('room'));
    }

    public function update(Request $request, SonetRoom $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:public,private,invite-only',
        ]);

        $room->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        return redirect()->route('rooms.show', $room)->with('success', 'Stanza aggiornata con successo.');
    }

    public function destroy(SonetRoom $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Stanza eliminata con successo.');
    }

    public function addMember(Request $request, SonetRoom $room)
    {
        if (!Auth::user()->character->isAdminInRoom($room->id)) {
            return redirect()->route('rooms.show', $room)->withErrors('Non hai i permessi per aggiungere membri.');
        }

        $request->validate([
            'character_id' => 'required|integer|exists:characters,id',
            'role' => 'required|in:admin,moderator,member',
        ]);

        $room->members()->create([
            'character_id' => $request->character_id,
            'role' => $request->role,
        ]);

        return redirect()->route('rooms.show', $room)->with('success', 'Membro aggiunto con successo.');
    }

    public function removeMember(Request $request, SonetRoom $room)
    {
        $request->validate([
            'character_id' => 'required|integer|exists:characters,id',
        ]);

        $room->members()->where('character_id', $request->character_id)->delete();

        return redirect()->route('rooms.show', $room)->with('success', 'Membro rimosso con successo.');
    }

    public function updateMemberRole(Request $request, SonetRoom $room)
    {
        $request->validate([
            'character_id' => 'required|integer|exists:characters,id',
            'role' => 'required|in:admin,moderator,member',
        ]);

        $member = $room->members()->where('character_id', $request->character_id)->firstOrFail();
        $member->update(['role' => $request->role]);

        return redirect()->route('rooms.show', $room)->with('success', 'Ruolo aggiornato con successo.');
    }
}
