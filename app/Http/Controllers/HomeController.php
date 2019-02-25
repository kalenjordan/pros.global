<?php

namespace App\Http\Controllers;

use App\SavedSearch;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $home = SavedSearch::findBySlugOrId('home');
        return view('home', [
            'home' => $home,
        ]);
    }
}
