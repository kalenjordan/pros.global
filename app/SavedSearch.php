<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder withAllTags($array)
 * @method static SavedSearch find($id)
 * @method static SavedSearch create($params)
 *
 * @property      $name
 * @property      $description
 * @property      $query
 * @property      $icon
 * @property      $slug
 * @property      $featured_order
 * @property User $user
 * @property      $updated_at
 */
class SavedSearch extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'query',
    ];

    public function getSlugOrId()
    {
        return $this->slug ? $this->slug : $this->id;
    }

    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    public static function findBySlugOrId($slugOrId)
    {
        $search = SavedSearch::findBySlug($slugOrId);
        if (!$search) {
            $search = SavedSearch::find($slugOrId);
        }

        return $search;
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['url'] = '/s/' . $this->getSlugOrId();

        return $data;
    }

    public function toArrayWithUsers()
    {
        $data = $this->toArray();
        $data['users'] = $this->fetchUsers();

        return $data;
    }

    public function fetchUsers()
    {
        $users = User::with('tags');

        $users->leftJoin("tagging_tagged_upvotes as upvotes", function ($join) {
            /** @var $join \Illuminate\Database\Query\JoinClause */
            $join->on("upvotes.tagged_user_id", '=', 'users.id');
        })->leftJoin("tagging_tagged as tagged", function ($join) {
            /** @var $join \Illuminate\Database\Query\JoinClause */
            $join->on("tagged.taggable_id", '=', 'users.id');
        })->select([
            'users.*',
        ])->groupBy('users.id');

        if ($this->query) {
            $search = new UserSearch($users);
            $search->query($this->query);
        }

        if (app('request')->input('show_sql')) {
            echo \SqlFormatter::format($users->toSql());
            exit;
        }

        return $users;
    }

    public function related()
    {
        return $this->belongsToMany('App\SavedSearch', 'saved_searches_related', 'saved_search_id',
            'related_saved_search_id');
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function relatedSavedSearches()
    {
        $self = $this;
        $savedSearches = SavedSearch::where('saved_searches.id', '>', 0)
            ->leftJoin("saved_searches_related", function ($join) use ($self) {
                /** @var $join \Illuminate\Database\Query\JoinClause */
                $join->on("saved_searches_related.saved_search_id", '=', DB::raw($self->id));
                $join->on("saved_searches_related.related_saved_search_id", '=', 'saved_searches.id');
            })->select([
                'saved_searches.*',
                'saved_searches_related.sort_order'
            ])->whereNotNull('saved_searches_related.id')
            ->orderBy('saved_searches_related.sort_order', 'desc');

        return $savedSearches;
    }

    public function toSearchIndexArray()
    {
        $data = $this->toArray();

        $data['type'] = 'saved-search';
        $data['object_id'] = $this->searchIndexId();

        return $data;
    }

    public function searchIndexId()
    {
        return env('APP_ENV') . '_savedsearch_' . $this->id;
    }
}
