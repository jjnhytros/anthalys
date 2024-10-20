<?php

namespace App\Http\Controllers\Anthaleja\Wiki;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\Wiki\WikiArticle;
use App\Models\Anthaleja\Wiki\WikiCategory;
use App\Models\Anthaleja\Character\Character;

class HomeController extends Controller
{
    public function homepage()
    {
        $featuredArticles = WikiArticle::where('is_featured', true)->take(5)->get();
        $latestArticles = WikiArticle::latest()->take(5)->get();
        $categories = WikiCategory::all();
        $articleCount = WikiArticle::count();
        $contributorCount = Character::has('articles')->count();

        return view('anthaleja.wiki.home', compact('featuredArticles', 'latestArticles', 'categories', 'articleCount', 'contributorCount'));
    }
}
