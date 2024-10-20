<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\SonetConnection;

class SonetConnectionController extends Controller
{
    /**
     * Display a listing of connection requests.
     */
    public function index()
    {
        $character = Auth::user()->character;

        // Get pending connection requests for the authenticated character
        $requests = SonetConnection::where('recipient_id', $character->id)
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        return view('anthaleja.sonet.connections.index', compact('requests'));
    }

    /**
     * Store a newly created connection.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|integer|exists:characters,id',
        ]);

        $character = Auth::user()->character;
        $recipientId = $request->input('recipient_id');

        // Check if a connection already exists
        $exists = SonetConnection::where(function ($query) use ($character, $recipientId) {
            $query->where('sender_id', $character->id)
                ->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($character, $recipientId) {
            $query->where('sender_id', $recipientId)
                ->where('recipient_id', $character->id);
        })->exists();

        if ($exists) {
            return response()->json(['message' => 'Connection already exists.'], 400);
        }

        // Create a new connection request
        SonetConnection::create([
            'sender_id' => $character->id,
            'recipient_id' => $recipientId,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Connection request sent successfully.']);
    }

    /**
     * Update the status of a connection request.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $character = Auth::user()->character;
        $status = $request->input('status');

        $connection = SonetConnection::where('id', $id)
            ->where('recipient_id', $character->id)
            ->firstOrFail();

        $connection->update(['status' => $status]);

        return response()->json(['message' => 'Connection status updated successfully.']);
    }

    /**
     * Remove a connection.
     */
    public function destroy($id)
    {
        $character = Auth::user()->character;

        $connection = SonetConnection::where(function ($query) use ($character, $id) {
            $query->where('id', $id)
                ->where(function ($subQuery) use ($character) {
                    $subQuery->where('sender_id', $character->id)
                        ->orWhere('recipient_id', $character->id);
                });
        })->firstOrFail();

        $connection->delete();

        return response()->json(['message' => 'Connection removed successfully.']);
    }
}
