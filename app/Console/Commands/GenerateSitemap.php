<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\SavedSearch;
use App\Tag;
use App\User;
use App\Date;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;


class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Generating sitemap");
        $sitemap = Sitemap::create();
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(Date::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.1));

        $users = User::all();
        $this->info($users->count() . " users");
        foreach ($users as $user) {
            $url = Url::create('/' . $user->username);
            if ($user->updated_at) {
                $url->setLastModificationDate($user->updated_at);
            }

            $sitemap->add($url);
        }

        $savedSearches = SavedSearch::all();
        $this->info($savedSearches->count() . " saved searches");
        foreach ($savedSearches as $savedSearch) {
            /** @var SavedSearch $savedSearch */
            $url = Url::create('/s/' . $savedSearch->getSlugOrId());
            if ($savedSearch->updated_at) {
                $url->setLastModificationDate($savedSearch->updated_at);
            }

            $sitemap->add($url);
        }

        $tags = Tag::all();
        $this->info($tags->count() . " tags");
        foreach ($tags as $tag) {
            /** @var Tag $tag */
            $url = Url::create('/tag/' . $tag->slug);
            if ($tag->updated_at) {
                $url->setLastModificationDate($tag->updated_at);
            }

            $sitemap->add($url);
        }

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        return;
    }
}
