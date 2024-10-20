<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Anthaleja\SoNet\ChatRoom;
use App\Models\Anthaleja\SoNet\SonetMessage;
use Intervention\Image\Laravel\Facades\Image;

class SonetMessageController extends Controller
{
    // Visualizza i messaggi di una chat room
    public function show(ChatRoom $room)
    {
        $character = Auth::user()->character;  // Recupera il character associato

        $messages = $room->sonetMessages()
            ->orderBy('created_at', 'asc')
            ->paginate(24);  // Paginazione a 20 messaggi

        return view('anthaleja.sonet.chat.show', compact('room', 'messages'));
    }

    public function store(Request $request, ChatRoom $room)
    {
        $request->validate([
            'message' => 'required|string',
            'reply_to' => 'nullable|exists:sonet_messages,id',
            'media' => 'nullable|mimes:jpeg,png,jpg,gif,mp4,mov,ogg,webm|max:50000', // File di media opzionale
            'media_url' => 'nullable|url', // URL opzionale
        ]);
        $messageText = $request->message;

        // Variabile che memorizzerà il valore finale
        $mediaPath = null;

        // Controlla se è stato caricato un file
        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $filename = time() . '.' . $media->getClientOriginalExtension();
            $mediaType = $media->getMimeType();

            if (strpos($mediaType, 'image') !== false) {
                // Gestione immagine
                $compressedImage = Image::make($media)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode($media->getClientOriginalExtension(), 75);

                Storage::disk('public')->put('uploads/images/' . $filename, $compressedImage);
                $mediaPath = 'uploads/images/' . $filename;
            } elseif (strpos($mediaType, 'video') !== false) {
                // Gestione video
                Storage::disk('public')->put('uploads/videos/' . $filename, file_get_contents($media));
                $mediaPath = 'uploads/videos/' . $filename;
            }
        }

        // Se è stato fornito un URL
        if ($request->media_url) {
            $mediaPath = $request->media_url;
        }
        preg_match_all('/@([a-zA-Z0-9_]+)/', $messageText, $mentions);
        SoNetMessage::create([
            'chat_room_id' => $room->id,
            'sender_id' => Auth::user()->character->id,  // Assumendo che il personaggio sia collegato all'utente
            'message' => $request->message,
            'type' => $request->hasFile('media') ? $request->file('media')->getClientOriginalExtension() : 'text',
            'media_url' => $mediaPath,
            'reply_to' => $request->reply_to,
        ]);

        return redirect()->route('sonet.chat.show', $room->id)->with('success', 'Messaggio inviato con successo.');
    }

    public function update(Request $request, ChatRoom $room, SoNetMessage $message)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if ($message->sender_id !== Auth::user()->character->id) {
            return redirect()->route('sonet.chat.show', $room->id)->with('error', 'Non puoi modificare questo messaggio.');
        }

        $message->update([
            'message' => $request->message,
            'edited' => true,
        ]);

        return redirect()->route('sonet.chat.show', $room->id)->with('success', 'Messaggio modificato con successo.');
    }

    public function destroy(ChatRoom $room, SoNetMessage $message)
    {
        // Verifica se l'utente è il mittente del messaggio
        if ($message->sender_id !== Auth::user()->character->id) {
            return redirect()->route('sonet.chat.show', $room->id)->with('error', 'Non puoi eliminare questo messaggio.');
        }

        $message->delete(); // Usa soft delete

        return redirect()->route('sonet.chat.show', $room->id)->with('success', 'Messaggio eliminato con successo.');
    }

    public function restore(ChatRoom $room, $messageId)
    {
        $message = SoNetMessage::withTrashed()->findOrFail($messageId);

        // Verifica se l'utente è il mittente del messaggio
        if ($message->sender_id !== Auth::user()->character->id) {
            return redirect()->route('sonet.chat.show', $room->id)->with('error', 'Non puoi ripristinare questo messaggio.');
        }

        $message->restore(); // Ripristina il messaggio

        return redirect()->route('sonet.chat.show', $room->id)->with('success', 'Messaggio ripristinato con successo.');
    }

    public function getNewMessages(ChatRoom $room, Request $request)
    {
        $lastMessageId = $request->lastMessageId; // ID dell'ultimo messaggio visibile nella chat

        $newMessages = SoNetMessage::where('chat_room_id', $room->id)
            ->where('id', '>', $lastMessageId) // Prende solo i messaggi nuovi
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($newMessages);
    }

    public function unreadMessagesCount()
    {
        $unreadCount = SoNetMessage::where('recipient_id', Auth::user()->character->id)
            ->where('status', 'unread')
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Usa Intervention Image per comprimere
            $compressedImage = Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode($image->getClientOriginalExtension(), 75); // Comprime con qualità al 75%

            // Salva l'immagine compressa
            Storage::disk('public')->put('uploads/' . $filename, $compressedImage);

            // Ritorna il percorso dell'immagine caricata
            return response()->json(['image_url' => asset('storage/uploads/' . $filename)]);
        }
    }
}
