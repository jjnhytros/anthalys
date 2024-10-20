<?php

namespace App\Http\Controllers\Anthaleja\SoNet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\SoNet\JobOffer;

class JobOfferController extends Controller
{
    public function index()
    {
        $jobOffers = JobOffer::latest()->get();
        return view('job_offers.index', compact('jobOffers'));
    }

    public function create()
    {
        return view('job_offers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'job_type' => 'required|in:freelance,full_time',
            'required_skills' => 'required|array',
            'negotiable' => 'required|boolean',
        ]);

        $character = Auth::user()->character;

        JobOffer::create([
            'character_id' => $character->id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary' => $request->salary,
            'job_type' => $request->job_type,
            'required_skills' => json_encode($request->required_skills),
            'negotiable' => $request->negotiable,
        ]);

        return redirect()->route('job_offers.index')->with('success', 'Offerta di lavoro pubblicata con successo');
    }

    public function recommendations()
    {
        $character = Auth::user()->character;
        $recommendedJobs = $character->jobRecommendations();

        return view('anthaleja.sonet.job_offers.recommendations', compact('recommendedJobs'));
    }

    public function search(Request $request)
    {
        $query = JobOffer::query();

        if ($request->has('skills')) {
            $skills = explode(',', $request->input('skills'));
            $query->where(function ($query) use ($skills) {
                foreach ($skills as $skill) {
                    $query->orWhereJsonContains('required_skills', $skill);
                }
            });
        }

        if ($request->has('job_type')) {
            $query->where('job_type', $request->input('job_type'));
        }

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        $jobOffers = $query->get();

        return view('anthaleja.job_offers.index', compact('jobOffers'));
    }
}
