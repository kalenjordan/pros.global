<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/users', function (Request $request) {
    $users = \App\User::all();
    return $users->toJson();
});

Route::get('/v1/users/{username}', function (Request $request, $username) {
    /** @var \App\User $user */
    $user = \App\User::with('tagged')->where('username', $username)->first();
    return $user->toArray();
});

Route::post('/v1/users/{username}/add-tag', 'UserController@addTag');

$tags = [
    [
        'tag'         => 'Bootstrapper',
        'slug'        => 'Bootstrapper',
        'description' => 'Lorem ipsum dolor set amen',
        'count'       => 15,
        'users'       => [
            [
                'name'       => 'Kalen',
                'avatar_url' => 'http://i.pravatar.cc/300?img=3'
            ],
            [
                'name'       => 'Bob',
                'avatar_url' => 'http://i.pravatar.cc/300?img=4'
            ],
        ]
    ],
    [
        'tag'         => 'Homeschool Dad',
        'slug'        => 'homeschool-dad',
        'description' => 'Lorem ipsum dolor set amen',
        'count'       => 3,
        'users'       => [
            [
                'name'       => 'Kalen',
                'avatar_url' => 'http://i.pravatar.cc/300?img=1'
            ],
            [
                'name'       => 'Bob',
                'avatar_url' => 'http://i.pravatar.cc/300?img=2'
            ],
        ]
    ],
    [
        'tag'         => 'Founder',
        'slug'        => 'founder',
        'description' => 'Lorem ipsum dolor set amen',
        'count'       => 13,
        'users'       => [
            [
                'name'       => 'Kalen',
                'avatar_url' => 'http://i.pravatar.cc/300?img=42'
            ],
            [
                'name'       => 'Kalen',
                'avatar_url' => 'http://i.pravatar.cc/300?img=43'
            ],
            [
                'name'       => 'Bob',
                'avatar_url' => 'http://i.pravatar.cc/300?img=44'
            ],
        ]
    ]
];

Route::get('/v1/tags', function (Request $request) use ($tags) {
    return $tags;
});

Route::get('/v1/tags/1', function (Request $request) use ($tags) {
    return $tags[0];
});
