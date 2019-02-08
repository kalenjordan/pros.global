<?php

use Illuminate\Http\Request;

Route::get('v1/saved-searches', 'SavedSearchController@list');
Route::get('v1/saved-searches/homepage', 'SavedSearchController@homepage');
Route::get('v1/saved-searches/{slug}', 'SavedSearchController@view');

Route::get('v1/tags', 'TagController@index');
Route::get('v1/tags/{slug}', 'TagController@view');

Route::get('v1/users', 'UserController@list');
Route::get('v1/users/{username}', 'UserController@view');
Route::get('v1/upvotes/{id}', 'UpvoteController@view');

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('v1/me', function (Request $request) {
        return $request->user();
    });

    Route::get('v1/messages/with-other-user/{otherUserId}', 'MessageController@withOtherUser');
    Route::post('v1/messages', 'MessageController@send');
    Route::get('v1/notifications', 'NotificationController@list');
    Route::get('v1/notifications/mark-read', 'NotificationController@markRead');

    Route::get('v1/twitter/add-user/{username}', 'TwitterController@addUser');

    Route::post('v1/users/{username}', 'UserController@post');
    Route::post('v1/users/{username}/add-tag', 'UserController@addTag');
    Route::get('v1/users/{username}/delete-tag/{tag}', 'UserController@deleteTag');
    Route::get('v1/users/{username}/upvote-tag/{tag}', 'UserController@upvoteTag');
    Route::get('v1/users/{username}/merge/{merging_username}', 'UserController@merge');

    Route::post('v1/upvotes/{id}', 'UpvoteController@post');
    Route::post('v1/saved-searches', 'SavedSearchController@create');
    Route::post('v1/saved-searches/{id}', 'SavedSearchController@edit');

});
