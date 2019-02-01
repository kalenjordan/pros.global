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

        if ($request->input('featured_min')) {
            $searches->where('featured_order', '>=', $request->input('featured_min'))
                ->orderBy('featured_order', 'desc')
                ->orderBy('name', 'asc');
        }

        if ($request->input('featured_max')) {
            $searches->where('featured_order', '<=', $request->input('featured_max'))
                ->orderBy('featured_order', 'desc');
        }

        $searches->orderBy('created_at', 'desc');

        if ($request->input('limit')) {
            $searches->limit($request->input('limit'));
        }

        $data = [];
        foreach ($searches->get() as $search) {
            /** @var SavedSearch $search */
            $data[] = $request->input('with_users') ? $search->toArrayWithUsers() : $search->toArray();
        }

        return $data;
    }

    public function view($slugOrId)
    {
        $search = SavedSearch::findBySlug($slugOrId);
        if (!$search) {
            $search = SavedSearch::find($slugOrId);
        }

        return $search->toArrayWithUsers();
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
