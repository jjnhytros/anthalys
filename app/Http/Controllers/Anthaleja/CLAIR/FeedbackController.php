<?php

namespace App\Http\Controllers\Anthaleja\CLAIR;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\CLAIR\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request, $interactionId)
    {
        Feedback::create([
            'interaction_id' => $interactionId,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Grazie per il tuo feedback!');
    }
}
