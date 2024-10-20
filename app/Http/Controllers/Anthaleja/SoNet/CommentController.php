<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\Comment;
use App\Models\Anthaleja\SoNet\SonetPost;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sonet_post_id' => 'required|exists:sonet_posts,id',
            'content' => 'required|string|max:1152',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($request->has('parent_id')) {
            // Se il commento ha un parent, eredita la visibilità del parent
            $parentComment = Comment::find($request->parent_id);
            $visibility = $parentComment->visibility;
        } else {
            // Se non ha un parent, eredita la visibilità del post
            $post = SonetPost::find($request->sonet_post_id);
            $visibility = $post->visibility;
        }
        // Crea un nuovo commento associato al post
        Comment::create([
            'sonet_post_id' => $request->sonet_post_id, // Assicurati che sonet_post_id sia passato correttamente
            'character_id' => Auth::user()->character->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Commento aggiunto con successo!');
    }
    public function fetchComments($sonetPostId)
    {
        $character = Auth::user()->character;

        try {
            // Recupera i commenti parent e i relativi figli ordinati dal più nuovo al più vecchio
            $comments = Comment::where('sonet_post_id', $sonetPostId)
                ->where(function ($query) use ($character) {
                    $query->where('visibility', 'public')
                        ->orWhere('visibility', 'follower')
                        ->whereHas('character.followers', function ($subQuery) use ($character) {
                            $subQuery->where('follower_id', $character->id);
                        })
                        ->orWhere('visibility', 'private')
                        ->where('character_id', $character->id)
                        ->orWhere(function ($query) use ($character) {
                            $query->where('visibility', 'mentioned')
                                ->whereHas('mentions', function ($subQuery) use ($character) {
                                    $subQuery->where('mentioned_id', $character->id);
                                });
                        });
                })
                ->latest() // Ordina i commenti dal più recente al più vecchio
                ->get();

            return view('anthaleja.sonet.partials.comment_list', compact('comments'))->render();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nel caricamento dei commenti'], 500);
        }
    }

    public function loadMoreComments(Request $request, $sonetPostId)
    {
        try {
            $offset = $request->input('offset', 0);
            $comments = Comment::where('sonet_post_id', $sonetPostId)
                ->with('character')
                ->latest()
                ->skip($offset)
                ->take(24)
                ->get();

            return view('anthaleja.sonet.comments.partials.comment_list', compact('comments'))->render();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore nel caricamento dei commenti'], 500);
        }
    }
}
