<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\SonetRoom;
use App\Models\Anthaleja\SoNet\SonetRoomMessage;

class SonetRoomMessageController extends Controller
{
    public function index(SonetRoom $room)
    {
        $messages = $room->messages()->with('sender')->latest()->paginate(10);
        return view('anthaleja.sonet.rooms.messages.index', compact('room', 'messages'));
    }

    public function store(Request $request, SonetRoom $room)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        SonetRoomMessage::create([
            'character_id' => Auth::user()->character->id,
            'message' => $request->message,
        ]);

        return redirect()->route('rooms.messages.index', $room)->with('success', 'Messaggio inviato con successo.');
    }

    public function edit(SonetRoom $room, SonetRoomMessage $message)
    {
        // Verifica che il messaggio appartenga alla stanza
        if ($message->room_id !== $room->id) {
            abort(403, 'Accesso negato.');
        }

        return view('anthaleja.sonet.rooms.edit_message', compact('room', 'message'));
    }

    public function update(Request $request, SonetRoom $room, SonetRoomMessage $message)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Verifica che il messaggio appartenga alla stanza
        if ($message->room_id !== $room->id) {
            abort(403, 'Accesso negato.');
        }

        // Aggiorna il messaggio
        $message->update([
            'message' => $request->input('message'),
        ]);

        return redirect()->route('rooms.show', $room)->with('success', 'Messaggio aggiornato con successo.');
    }

    public function destroy(SonetRoom $room, SonetRoomMessage $message)
    {
        // Verifica che il messaggio appartenga alla stanza
        if ($message->room_id !== $room->id) {
            abort(403, 'Accesso negato.');
        }
        $message->delete();

        return redirect()->route('rooms.show', $room)->with('success', 'Messaggio eliminato con successo.');
    }
}
