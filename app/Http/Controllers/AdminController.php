<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

use App\Tagged;
use App\UserSearch;
use App\User;

class AdminController extends Controller
{

    public function impersonate(Request $request, $username)
    {
        if (! Auth::user() || ! Auth::user()->is_admin) {
            die('Not authorized');
        }

        if (! $user = User::findByUsername($username) ) {
            die('User not found: ' . $username);
        }

        Auth::user()->impersonate($user);

        return $user->toArrayForCookie();
    }

    public function impersonateLeave(Request $request) {
        Auth::user()->leaveImpersonation();
        return Auth::user()->toArrayForCookie();
    }
}
