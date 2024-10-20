<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Profile;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'bio' => 'nullable|string',
            'skills' => 'nullable|array',
        ]);

        $profile->update([
            'bio' => $request->bio,
            'skills' => $request->skills ? json_encode($request->skills) : null,
        ]);

        return redirect()->back()->with('success', 'Profilo aggiornato con successo');
    }
}
