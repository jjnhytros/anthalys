<?php

namespace App\Http\Controllers\Anthaleja\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Anthaleja\SoNet\SonetComment;
use App\Models\Anthaleja\SoNet\SonetMessage;

class FederationController extends Controller
{
    // Invia messaggio a un'istanza remota
    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'to_instance' => 'required|string',
            'to_character' => 'required|string',
            'message_content' => 'required|string',
        ]);

        // Invia la richiesta all'API dell'istanza remota
        $response = Http::post($data['to_instance'] . '/api/sonet/receive-message', [
            'to_character' => $data['to_character'],
            'message_content' => $data['message_content'],
            'from_instance' => url('/'),
        ]);

        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Messaggio inviato con successo.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Errore nell\'invio del messaggio.'], 500);
    }

    // Ricevi messaggio da un'istanza remota
    public function receiveMessage(Request $request)
    {
        $data = $request->validate([
            'to_character' => 'required|string',
            'message_content' => 'required|string',
            'from_instance' => 'required|string',
        ]);

        // Crea e salva il messaggio
        $message = new SonetMessage();
        $message->chat_room_id = null;
        $message->sender_id = null;
        $message->message = $data['message_content'];
        $message->is_instance_message = true;
        $message->from_instance = $data['from_instance'];
        $message->save();

        return response()->json(['status' => 'success', 'message' => 'Messaggio ricevuto e salvato con successo.']);
    }

    // Invia un post o un commento a un'istanza remota
    public function postContent(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|string',
            'to_instance' => 'required|string|url',
            'content' => 'required|string',
            'visibility' => 'required|string',
            'character_id' => 'required|exists:characters,id',
        ]);

        // Invia il contenuto all'API dell'istanza remota
        $response = Http::post($data['to_instance'] . '/api/sonet/receive-post', [
            'post_id' => $data['post_id'],
            'content' => $data['content'],
            'character_id' => $data['character_id'],
            'visibility' => $data['visibility'],
            'from_instance' => url('/'), // Aggiunge l'URL dell'istanza locale
        ]);

        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Post inviato con successo.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Errore nell\'invio del post.'], 500);
    }

    public function sendComment(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|string',
            'to_instance' => 'required|string',
            'content' => 'required|string',
            'character_id' => 'required|exists:characters,id',
        ]);

        // Invia il commento all'API dell'istanza remota
        $response = Http::post($data['to_instance'] . '/api/sonet/receive-comment', [
            'post_id' => $data['post_id'],
            'content' => $data['content'],
            'character_id' => $data['character_id'],
            'from_instance' => url('/'),
        ]);

        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Commento inviato con successo.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Errore nell\'invio del commento.'], 500);
    }

    // Ricevi un commento da un'istanza remota
    public function receiveComment(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|string',
            'content' => 'required|string',
            'from_instance' => 'required|string',
            'character_id' => 'required|exists:characters,id',
        ]);

        // Crea il commento ricevuto
        $comment = SonetComment::create([
            'post_id' => $data['post_id'],
            'content' => $data['content'],
            'character_id' => $data['character_id'],
            'from_instance' => $data['from_instance'],
        ]);

        return response()->json(['message' => 'Commento ricevuto con successo', 'comment' => $comment]);
    }

    public function sharePost(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|string',
            'to_instance' => 'required|string',
            'content' => 'required|string',
        ]);

        // Invia il post all'API dell'istanza remota
        $response = Http::post($data['to_instance'] . '/api/sonet/receive-post', [
            'post_id' => $data['post_id'],
            'content' => $data['content'],
            'from_instance' => url('/'),
        ]);

        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Post condiviso con successo.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Errore nella condivisione del post.'], 500);
    }

    public function receivePost(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|string',
            'content' => 'required|string',
            'from_instance' => 'required|string', // Identificativo dell'istanza remota
        ]);

        // Crea e salva il post ricevuto dall'istanza remota
        $post = new SonetMessage();  // Trattiamo i post come messaggi
        $post->message = $data['content'];
        $post->is_instance_message = true;
        $post->from_instance = $data['from_instance'];
        $post->save();

        return response()->json(['status' => 'success', 'message' => 'Post ricevuto e salvato con successo.']);
    }

    public function getPostComments($postId)
    {
        // Recupera i commenti relativi a un post da un'istanza remota
        $comments = SonetMessage::where('reply_to', $postId)->get();

        return response()->json($comments);
    }
}
