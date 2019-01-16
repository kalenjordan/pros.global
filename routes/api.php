<?php

use Illuminate\Http\Request;

Route::get('/v1/users', 'UserController@list');
Route::get('/v1/users/{username}', 'UserController@view');
Route::get('/v1/tags', 'TagController@index');
Route::get('/v1/tags/{slug}', 'TagController@view');
Route::get('v1/upvotes/{id}', 'UpvoteController@view');

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::post('/v1/users/{username}', 'UserController@post');
    Route::post('/v1/users/{username}/add-tag', 'UserController@addTag');
    Route::get('/v1/users/{username}/delete-tag/{tag}', 'UserController@deleteTag');
    Route::get('/v1/users/{username}/upvote-tag/{tag}', 'UserController@upvoteTag');
    Route::post('v1/upvotes/{id}', 'UpvoteController@addMessage');
});
