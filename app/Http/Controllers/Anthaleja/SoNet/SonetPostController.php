<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Models\Anthaleja\SoNet\Ad;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\SonetPost;

class SonetPostController extends Controller
{
    public function index(Request $request)
    {
        $character = Auth::user()->character;

        $sonets = SonetPost::with('character')
            ->where(function ($query) use ($character) {
                $query->where('visibility', 'public')
                    ->orWhere('visibility', 'follower')
                    ->orWhere('visibility', 'private')->where('character_id', $character->id)
                    ->orWhere(function ($query) use ($character) {
                        $query->where('visibility', 'mentioned')
                            ->whereHas('mentions', function ($subQuery) use ($character) {
                                $subQuery->where('mentioned_id', $character->id);
                            });
                    });
            })
            ->latest()
            ->paginate(12);

        foreach ($sonets as $sonet) {
            if (!empty($sonet->media)) {
                $extension = strtolower(pathinfo($sonet->media, PATHINFO_EXTENSION));
                $sonet->icon = $this->getFileTypeIcon($extension);
            }
        }

        $ads = [];
        if ($character->prefersAds()) {
            $ads = Ad::where('active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->get();
        }


        if ($request->ajax()) {
            return view('anthaleja.sonet.partials.sonets', compact('sonets'))->render();
        }

        return view('anthaleja.sonet.posts.timeline', compact('sonets', 'ads'));
    }

    public function create()
    {
        return view('anthaleja.sonet.posts.create');
    }

    public function storeOrUpdate(Request $request)
    {
        $rules = [
            'content' => 'required|string|max:1152',
            'visibility' => 'required|string|in:follower,public,private,mentioned',
            'publish_at' => 'nullable|date|after:now',
            'expires_at' => 'nullable|date|after:today',
        ];


        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mimeType = $file->getMimeType();

            if (preg_match('/^image\//', $mimeType)) {
                $rules['media'] = 'file|mimes:jpg,jpeg,png,gif|max:5120';
            } elseif (preg_match('/^video\//', $mimeType)) {
                $rules['media'] = 'file|mimes:mp4,mov,ogg,webm|max:51200';
            } else {
                $rules['media'] = 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,rtf,odt,ods,odp,epub,mobi,html,xml,json,zip,rar|max:20480';
            }
        }

        $request->validate($rules);

        $character = Auth::user()->character;
        $mediaPath = $request->hasFile('media') ? $request->file('media')->store('media', 'public') : null;

        if ($request->has('id')) {
            $sonet = SonetPost::findOrFail($request->id);
            $sonet->update([
                'content' => $request->input('content'),
                'media' => $mediaPath ?? $sonet->media,
                'visibility' => $request->input('visibility'),
                'publish_at' => $request->input('publish_at'),
                'expires_at' => $request->input('expires_at'),
            ]);
            $message = 'Sonet aggiornato con successo!';
        } else {
            SonetPost::create([
                'character_id' => $character->id,
                'content' => $request->input('content'),
                'media' => $mediaPath,
                'visibility' => $request->input('visibility'),
                'publish_at' => $request->input('publish_at'),
                'expires_at' => $request->input('expires_at'),
            ]);
            $message = 'Sonet pubblicato con successo!';
        }

        return redirect()->route('timeline')->with('success', $message);
    }

    public function edit($id)
    {
        $post = SonetPost::findOrFail($id);
        return view('anthaleja.sonet.posts.edit', compact('post'));
    }

    public function destroy($id)
    {
        $post = SonetPost::findOrFail($id);
        $post->delete();

        return redirect()->route('sonet.posts.index')->with('success', 'Post eliminato con successo.');
    }

    public function loadMore(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = 24;
        $offset = ($page - 1) * $limit;

        $sonets = SonetPost::with('character', 'lumina', 'comments')
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get();

        return view('anthaleja.sonet.posts.partials.sonet_list', compact('sonets'))->render();
    }

    private function getFileTypeIcon($extension)
    {
        return getFileTypeIcon($extension);
    }
}
