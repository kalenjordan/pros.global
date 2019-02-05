<?php

namespace App\Http\Controllers;

use App\SavedSearch;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use App\Date;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{

    public function generate()
    {
        $sitemap = Sitemap::create();
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(Date::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.1));

        foreach (User::all() as $user) {
            $url = Url::create('/' . $user->username);
            if ($user->updated_at) {
                $url->setLastModificationDate($user->updated_at);
            }

            $sitemap->add($url);
        }

        foreach (SavedSearch::all() as $savedSearch) {
            /** @var SavedSearch $savedSearch */
            $url = Url::create('/s/' . $savedSearch->getSlugOrId());
            if ($savedSearch->updated_at) {
                $url->setLastModificationDate($savedSearch->updated_at);
            }

            $sitemap->add($url);
        }

        foreach (Tag::all() as $tag) {
            /** @var Tag $tag */
            $url = Url::create('/search/tag:' . $tag->slug);
            if ($tag->updated_at) {
                $url->setLastModificationDate($tag->updated_at);
            }

            $sitemap->add($url);
        }

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        return redirect('/sitemap.xml');
    }
}
