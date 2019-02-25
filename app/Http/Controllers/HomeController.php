<?php

namespace App\Http\Controllers;

use App\SavedSearch;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $home = SavedSearch::findBySlugOrId('home');
        $footerSavedSearches = SavedSearch::where('featured_order', '>=', 10)
            ->where('featured_order', '<=', 99);

        return view('home', [
            'home' => $home,
            'footerSavedSearches' => $footerSavedSearches,
        ]);
    }

    public function twitterCard()
    {
        return view('twitter-cards.home');
    }
}
