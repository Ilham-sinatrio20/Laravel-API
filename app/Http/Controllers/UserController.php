<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function showUser($username){
        return User::where('username', $username)->select('username', 'created_at', 'email')->first();
    }

    public function getActivity($username){
        return new UserResource(
            User::where('username', $username)
            ->first()
        );
    }
}
