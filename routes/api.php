<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$users = [
    [
        'id'          => 1,
        'name'        => 'Kalen Jordan',
        'handle'      => '@kalenjordan',
        'image_url'   => 'https://pbs.twimg.com/profile_images/959539398210547712/U5lQBX2N_400x400.jpg',
        'city'        => 'Austin',
        'tags'        => [
            [
                'tag'        => 'Bootstrapper',
                'count'      => 3,
                'is_upvoted' => 1,
            ],
            [
                'tag'        => 'Founder',
                'count'      => 1,
                'is_upvoted' => 0,
            ],
            [
                'tag'        => 'Magento',
                'count'      => 5,
                'is_upvoted' => 0,
            ],
            [
                'tag'        => 'Laravel',
                'count'      => 1,
                'is_upvoted' => 0,
            ],
            [
                'tag'        => 'Homeschool Dad',
                'count'      => 0,
                'is_upvoted' => 0,
            ],
            [
                'tag'        => 'PHP',
                'count'      => 1,
                'is_upvoted' => 0,
            ],
            [
                'tag'        => 'eCommerce',
                'count'      => 1,
                'is_upvoted' => 0,
            ],
        ],
        'headline' => 'Magento fanboy. Small-time entrepreneur. Wannabe Youtuber. Not a recruiter. Founder @commercehero. Co-host @magetalk',
        'about' => "
            Hey there! I'm Kalen! Glad you're here checking out my page on Founder Land. I'm building Founder Land because I love to connect people and I love the bootstrapped founder community.

            Little about me…I've been in the Magento community for about 7 years, and I built an email software that was acquired, started a podcast that I cohost with my buddy, and then built a matchmaking platform to find Magento developers, which is doing great and I continue to run.
        "
    ],
    [
        'id'          => 2,
        'name'        => 'Andrew Culver',
        'handle'      => '@andrewculver',
        'image_url'   => 'https://pbs.twimg.com/profile_images/976527971140845568/iniQmnYi_400x400.jpg',
        'city'        => 'Los Angeles',
        'tags'        => [
            [
                'tag'        => 'Bootstrapper',
                'count'      => 3,
                'is_upvoted' => 0,
            ],
            [
                'tag'        => 'Founder',
                'count'      => 1,
                'is_upvoted' => 0,
            ],
        ],
        'headline' => 'Founder and Lead Developer at bullettrain.co . Founded and sold churnbuster.io . Bootstrapper. Remote. Canadian. Love️ Japan.',
        'about' => 'test',
    ]
];

Route::get('/v1/users', function (Request $request) use ($users) {
    return $users;
});

Route::get('/v1/users/{id}', function (Request $request) use ($users) {
    return $users[0];
});
