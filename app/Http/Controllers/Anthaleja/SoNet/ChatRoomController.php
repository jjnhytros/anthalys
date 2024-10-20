<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\ChatRoom;
use App\Models\Anthaleja\SoNet\SoNetMessage;
use App\Models\Anthaleja\Character\Character;
use App\Models\Anthaleja\SoNet\SoNetChatRoom;

class ChatRoomController extends Controller
{
    public function index()
    {
        $chatRooms = SoNetChatRoom::all();
        // return view('anthaleja.sonet.chat.index', compact('chatRooms'));
    }

    public function show($id)
    {
        $chatRoom = SoNetChatRoom::findOrFail($id);
        $messages = SoNetMessage::where('chat_room_id', $chatRoom->id)->orderBy('created_at', 'asc')->get();
        return view('anthaleja.sonet.chat.show', compact('chatRoom', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        SoNetChatRoom::create([
            'name' => $request->name,
            'created_by' => Auth::user()->character->id,
        ]);

        // return redirect()->route('sonet.chat.index')->with('success', 'Chat room created successfully.');
    }

    public function createPrivateChat(Character $user)
    {
        // Verifica se già esiste una chat privata tra i due utenti
        $existingRoom = SoNetChatRoom::where('type', 'private')
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('character_id', Auth::user()->character->id)
                    ->orWhere('character_id', $user->id);
            })->first();

        if (!$existingRoom) {
            $room = SoNetChatRoom::create([
                'type' => 'private',
                'created_by' => Auth::user()->character->id,
            ]);
            $room->participants()->attach([Auth::user()->character->id, $user->id]);
        }

        return redirect()->route('chat.show', $existingRoom ? $existingRoom->id : $room->id);
    }

    public function createGroupChat(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'participants' => 'required|array', // Lista di ID utenti
        ]);

        $room = SoNetChatRoom::create([
            'name' => $request->name,
            'type' => 'group',
            'created_by' => Auth::user()->character->id,
        ]);

        $room->participants()->attach($request->participants); // Aggiunge i partecipanti al gruppo

        return redirect()->route('chat.show', $room->id);
    }
}
