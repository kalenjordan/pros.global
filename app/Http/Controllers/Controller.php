<?php

namespace App\Http\Controllers;

use View;

use App\SavedSearch;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $footerSavedSearches = SavedSearch::where('featured_order', '>=', 10)
            ->where('featured_order', '<=', 99);

        View::share('footerSavedSearches', $footerSavedSearches);
    }
}
