<?php

namespace App\Http\Controllers\Anthaleja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('anthaleja.home');
    }
    public function wiki()
    {
        return view('anthaleja.wiki.wiki');  // Assicurati che la vista 'wiki.home' esista
    }
}
