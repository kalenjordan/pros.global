<?php

namespace App\Http\Controllers;

use Auth;
use App\SavedSearch;
use Illuminate\Http\Request;

class SavedSearchController extends Controller
{
    public function create(Request $request) {
        $search = SavedSearch::create([
            'user_id' => Auth::user()->id,
            'name' => $request->input('name'),
            'query' => $request->input('query'),
        ]);

        return $search;
    }

    public function list(Request $request) {
        $searches = SavedSearch::where('id', '>', 0);
        if ($request->input('featured')) {
            $searches->where('featured_order', '>', 0);
            $searches->orderBy('featured_order', 'desc');
        } else {
            $searches->orderBy('created_at', 'desc');
        }

        return $searches->get();
    }
}
