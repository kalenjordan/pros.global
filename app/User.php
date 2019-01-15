<?php

namespace App;

use Conner\Tagging\Taggable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @package App
 * @method static \Illuminate\Database\Query\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder withAllTags($array)
 *
 * @property $about
 * @property $headline
 */
class User extends Authenticatable
{
    use Notifiable;
    use Taggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
    ];

    public static function findByUsername($username)
    {
        return self::with('tagged')->where('username', $username)->first();
    }

    public function toArray()
    {
        $data = parent::toArray();

        // The default tagged array isn't quite right
        unset($data['tagged']);

        $data['tags'] = $this->getTagged()->toArray();
        $data['upvotes'] = $this->_toArrayUpvotes();

        return $data;
    }

    public function getTagged() {
        return Tagged::where('taggable_id', $this->id)->get();
    }

    public function tagsArray()
    {
        $tags = $this->tags;
        foreach ($this->tags as $tag) {
            $tag->is_upvoted = 1;
        }

        return $tags->toArray();
    }

    protected function _toArrayUpvotes()
    {
        $upvotes = TaggedUpvote::from('tagging_tagged_upvotes as upvotes')
            ->leftJoin('tagging_tagged as tagged', function ($join) {
                /** @var $join \Illuminate\Database\Query\JoinClause */
                $join->on('tagged.id', '=', 'upvotes.tagged_id');
            })->where('tagged.taggable_id', $this->id)
            ->get();

        return $upvotes->toArray();

        return [
            [
                'id'      => 1,
                'user'    => [
                    'first_name' => 'Joe',
                    'avatar_url' => 'http://i.pravatar.cc/300?img=1',
                ],
                'tag'     => 'Founder',
                'message' => "Kalen is the best, he's so awesome",
            ],
            [
                'id'      => 2,
                'user'    => [
                    'first_name' => 'Joe',
                    'avatar_url' => 'http://i.pravatar.cc/300?img=1',
                ],
                'tag'     => 'PHP',
                'message' => "Kalen is really great when it comes to PHP - really knows his stuff"
            ]
        ];
    }
}
