<?php

namespace App\Http\Controllers;

use App\SavedSearch;
use App\TaggedUpvote;
use Illuminate\Http\Request;
use App\User;
use App\Tag;
use App\Tagged;

class SavedSearchController extends Controller
{
    public function post(Request $request, $id) {
        // todo implement
    }

    public function list(Request $request) {
        $searches = SavedSearch::where('id', '>', 0);
        if ($request->input('featured')) {
            $searches->where('featured_order', '>', 0);
            $searches->orderBy('featured_order', 'desc');
        }

        return $searches->get();
    }
}
