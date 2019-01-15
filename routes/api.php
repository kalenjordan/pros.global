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
Route::get('/v1/users/{username}/delete-tag/{tag}', 'UserController@deleteTag');
Route::get('/v1/users/{username}/upvote-tag/{tag}', 'UserController@upvoteTag');

Route::get('/v1/tags', 'TagController@index');
Route::get('/v1/tags/{slug}', 'TagController@view');

Route::get('/v1/upvotes/{id}', function (Request $request, $id) {
    /** @var \App\TaggedUpvote $upvote */
    $upvote = \App\TaggedUpvote::with('user')->find($id);

    return $upvote->toArray();
});