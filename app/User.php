<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;

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

    public function toArray()
    {
        $data = parent::toArray();

        $data['tags'] = $this->_buildTags();
        $data['endorsements'] = $this->_buildEndorsements();

        return $data;
    }

    protected function _buildTags()
    {
        return [
            [
                'id'         => 1,
                'tag'        => 'Austin',
                'count'      => 3,
                'is_upvoted' => 1,
            ],
            [
                'id'         => 2,
                'tag'        => 'Bootstrapper',
                'count'      => 3,
                'is_upvoted' => 1,
            ],
            [
                'id'         => 3,
                'tag'        => 'Founder',
                'count'      => 1,
                'is_upvoted' => 0,
            ],
            [
                'id'         => 4,
                'tag'        => 'Magento',
                'count'      => 5,
                'is_upvoted' => 0,
            ],
        ];
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
