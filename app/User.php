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
    ];

    public static function findByUsername($username)
    {
        return self::where('username', $username)->first();
    }

    public function toArray()
    {
        $data = parent::toArray();

        unset($data['tagged']); // don't need the tagged stuff that the rtconner package adds
        $data['tags'] = $this->tagsArray();
        $data['endorsements'] = $this->_buildEndorsements();

        return $data;
    }

    public function tagsArray()
    {
        $tags = $this->tags;
        foreach ($this->tags as $tag) {
            $tag->is_upvoted = 1;
        }

        return $tags->toArray();
    }

    protected function _buildEndorsements()
    {
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
