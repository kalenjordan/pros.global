<?php

namespace App\Http\Controllers;

use Auth;
use App\SavedSearch;
use Illuminate\Http\Request;

class SavedSearchController extends Controller
{

    public function create(Request $request)
    {
        $search = SavedSearch::create([
            'user_id' => Auth::user()->id,
            'name'    => $request->input('name'),
            'query'   => $request->input('query'),
        ]);

        return $search;
    }

    public function edit(Request $request, $id)
    {
        $search = SavedSearch::find($id);

        $search->name = $request->input('name');
        $search->query = $request->input('query');
        $search->featured_order = $request->input('featured_order');
        $search->slug = $request->input('slug');

        $search->save();

        return $search;
    }

    public function list(Request $request)
    {
        $searches = SavedSearch::where('id', '>', 0);

        if ($request->input('featured')) {
            $searches->where('featured_order', '>', 0)
                ->where('featured_order', '!=', 100);
            $searches->orderBy('featured_order', 'desc');
        } else {
            $searches->orderBy('created_at', 'desc');
        }

        return $searches->get();
    }

    public function view($slugOrId)
    {
        $search = SavedSearch::findBySlug($slugOrId);
        if (!$search) {
            $search = SavedSearch::find($slugOrId);
        }

        return $search;
    }

    public function homepage(Request $request)
    {
        $search = SavedSearch::where('user_id', env('ADMIN_USER_ID'))
            ->where('featured_order', 100)
            ->first();

        return $search;
    }

    public function viewHtml($slugOrId)
    {
        $search = SavedSearch::findBySlug($slugOrId);
        if (!$search) {
            $search = SavedSearch::find($slugOrId);
        }

        return view('saved-search', [
            'savedSearch' => $search,
        ]);
    }

    public function twitterCard($slugOrId)
    {
        $search = SavedSearch::findBySlug($slugOrId);
        if (!$search) {
            $search = SavedSearch::find($slugOrId);
        }

        return view('twitter-card-saved-search', [
            'savedSearch' => $search,
        ]);
    }
}
