<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function addTag(Request $request, $username) {
        /** @var \App\User $user */
        $user = User::where('username', $username)->first();
        $tag = $request->input('tag');
        $user->tag($tag);

        return $user->tagsArray();
    }
}
