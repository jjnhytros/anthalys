<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\JobOffer;
use App\Models\Anthaleja\SoNet\JobReview;

class JobReviewController extends Controller
{
    public function store(Request $request, $jobOfferId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:12',
            'review' => 'required|string|max:255',
        ]);

        $reviewer = Auth::user()->character;

        JobReview::create([
            'job_offer_id' => $jobOfferId,
            'reviewer_id' => $reviewer->id,
            'reviewed_id' => JobOffer::findOrFail($jobOfferId)->character_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Recensione aggiunta con successo');
    }
}
