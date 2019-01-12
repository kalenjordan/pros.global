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

Route::get('/v1/users', function (Request $request) {
    return [
        [
            'id'          => 1,
            'name'        => 'Kalen Jordan',
            'handle'      => '@kalenjordan',
            'image_url'   => 'https://pbs.twimg.com/profile_images/959539398210547712/U5lQBX2N_400x400.jpg',
            'city'        => 'Austin',
            'tags'        => [
                'Bootstrapper',
                'Founder',
                'Magento',
                'Laravel',
                'Homeschool Dad',
                'SaaS',
                'Seeking Advisees',
                'Developer',
            ],
            'description' => 'Magento fanboy. Small-time entrepreneur. Wannabe Youtuber. Not a recruiter. Founder @commercehero. Co-host @magetalk',
        ],
        [
            'id'          => 2,
            'name'        => 'Andrew Culver',
            'handle'      => '@andrewculver',
            'image_url'   => 'https://pbs.twimg.com/profile_images/976527971140845568/iniQmnYi_400x400.jpg',
            'city'        => 'Los Angeles',
            'tags'        => [
                'Founder',
                'Rails',
                'SaaS',
                'Japanophile',
                'Bootstrapper',
            ],
            'description' => 'Founder and Lead Developer at bullettrain.co . Founded and sold churnbuster.io . Bootstrapper. Remote. Canadian. Love️ Japan.',
        ]
    ];
});

Route::get('/v1/users/{id}', function (Request $request) {
    return [
        'id'          => 1,
        'name'        => 'Kalen Jordan',
        'handle'      => '@kalenjordan',
        'image_url'   => 'https://pbs.twimg.com/profile_images/959539398210547712/U5lQBX2N_400x400.jpg',
        'city'        => 'Austin',
        'tags'        => [
            'Bootstrapper',
            'Founder',
            'Magento',
            'Laravel',
            'Homeschool Dad',
            'SaaS',
            'Seeking Advisees',
            'Developer',
        ],
        'description' => 'Magento fanboy. Small-time entrepreneur. Wannabe Youtuber. Not a recruiter. Founder @commercehero. Co-host @magetalk',
    ];
});
