<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Character\Character;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function follow($id)
    {
        $character = Auth::user()->character;
        $characterToFollow = Character::findOrFail($id);

        $character->follow($characterToFollow);

        return response()->json(['message' => 'Hai iniziato a seguire ' . $characterToFollow->username]);
    }

    public function unfollow($id)
    {
        $character = Auth::user()->character;
        $characterToUnfollow = Character::findOrFail($id);

        $character->unfollow($characterToUnfollow);

        return response()->json(['message' => 'Hai smesso di seguire ' . $characterToUnfollow->username]);
    }
}
