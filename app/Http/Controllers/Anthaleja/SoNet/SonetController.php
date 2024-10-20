<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Anthaleja\SoNet\SonetPost;

class SonetController extends Controller
{

    public function index(Request $request)
    {
        $character = Auth::user()->character;

        $allSonets = SonetPost::with('character', 'comments')
            ->visibleTo($character)
            ->latest()
            ->get()
            ->toArray();

        // Ordina i post per data di creazione (created_at)
        usort($allSonets, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Ritorna la vista con i post
        return view('anthaleja.sonet.posts.timeline', ['sonets' => $allSonets]);
    }

    public function create()
    {
        return view('anthaleja.sonet.create');
    }

    public function store(Request $request)
    {
        // Validazione dei dati
        $request->validate([
            'content' => 'required|string|max:1152',
            'visibility' => 'required|string|in:follower,public,private,mentioned',
            'media' => 'nullable|file|mimes:jpeg,png,gif,mp4,webm|max:51200',
            'publish_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $character = Auth::user()->character;
        $mediaPath = null;

        // Salva il file se presente
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('media', 'public');
        }

        // Crea il post
        SonetPost::create([
            'character_id' => $character->id,
            'content' => $request->content,
            'media' => $mediaPath,
            'visibility' => $request->visibility,
            'publish_at' => $request->publish_at,
            'expires_at' => $request->expires_at,
        ]);

        // Ritorna alla timeline con un messaggio di successo
        return redirect()->route('timeline')->with('success', 'Sonet pubblicato con successo!');
    }

    public function edit($id)
    {
        $post = SonetPost::findOrFail($id);
        return view('anthaleja.sonet.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'visibility' => 'required|string',
        ]);

        $post = SonetPost::findOrFail($id);
        $post->update($request->all());

        return redirect()->route('sonet.index')->with('success', 'Post aggiornato con successo.');
    }

    public function destroy($id)
    {
        $post = SonetPost::findOrFail($id);
        $post->delete();

        return redirect()->route('sonet.index')->with('success', 'Post eliminato con successo.');
    }

    public function deleteMedia($id)
    {
        $sonet = SonetPost::findOrFail($id);
        if ($sonet->media) {
            Storage::disk('public')->delete($sonet->media);
            $sonet->update(['media' => null]);
        }

        return redirect()->back()->with('success', 'File multimediale rimosso con successo.');
    }
}
