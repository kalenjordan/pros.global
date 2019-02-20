<?php

namespace App\Console\Commands;

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\SearchIndex;
use App\SavedSearch;
use App\Tag;
use App\User;
use Illuminate\Console\Command;

class AlgoliaIndex extends Command
{
    /** @var SearchIndex */
    protected $index;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'algolia:index {--limit=} {--v}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send data to algolia index';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function _limit()
    {
        return $this->option('limit') ? $this->option('limit') : 5;
    }

    /**
     * @throws \Algolia\AlgoliaSearch\Exceptions\MissingObjectId
     */
    public function handle()
    {
        $client = SearchClient::create(
            env('ALGOLIA_APP_ID'),
            env('ALGOLIA_PUBLIC_KEY')
        );

        $this->index = $client->initIndex(env('APP_ENV') . '_search');

        $this->info("Updating Algolia search index (limit: " . $this->_limit() . ")");

        $users = User::where('id', '>', 0)
            ->limit($this->_limit());

        $this->info($users->count() . " users");
        foreach ($users->get() as $user) {
            $this->_indexUser($user);
        }

        $tags = Tag::where('id', '>', 0)
            ->limit($this->_limit());

        $this->info($tags->count() . " tags");
        foreach ($tags->get() as $tag) {
            $this->_indexTag($tag);
        }

        $searches = SavedSearch::where('id', '>', 0)
            ->limit($this->_limit());

        $this->info($searches->count() . " saved searches");
        foreach ($searches->get() as $search) {
            $this->_indexSavedSearch($search);
        }

        return;
    }

    /**
     * @param User $user
     *
     * @throws \Algolia\AlgoliaSearch\Exceptions\MissingObjectId
     */
    protected function _indexUser(User $user) {
        $this->info("Indexing user: " . $user->name  . " - " . $user->searchIndexId());
        $data = $user->toSearchIndexArray();
        if ($this->option('v')) {
            foreach ($data as $key => $val) {
                $this->info("    - $key: $val");
            }
        }
        $this->index->saveObjects([$data], [
            'objectIDKey' => 'object_id',
        ]);
    }

    /**
     * @param User $user
     *
     * @throws \Algolia\AlgoliaSearch\Exceptions\MissingObjectId
     */
    protected function _indexTag(Tag $tag) {
        $this->info("Indexing tag: " . $tag->name  . " - " . $tag->searchIndexId());
        $this->index->saveObjects([$tag->toSearchIndexArray()], [
            'objectIDKey' => 'object_id',
        ]);
    }

    /**
     * @param User $user
     *
     * @throws \Algolia\AlgoliaSearch\Exceptions\MissingObjectId
     */
    protected function _indexSavedSearch(SavedSearch $search) {
        $this->info("Indexing saved search: " . $search->name  . " - " . $search->searchIndexId());
        $this->index->saveObjects([$search->toSearchIndexArray()], [
            'objectIDKey' => 'object_id',
        ]);
    }
}
