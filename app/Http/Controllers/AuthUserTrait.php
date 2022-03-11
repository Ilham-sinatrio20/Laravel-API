<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

trait AuthUserTrait{
    private function getAuthorized(){
        try {
            return auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $th) {
            response()->json(['message' => 'not authentication, you have to login'])->send();
            exit;
        }
    }

    public function checkOwnership($owner){
        $user = $this->getAuthorized();
        if($user->id != $owner){
            response()->json(['message' => 'You are not authorized to update this forum'])->send();
            exit();
        }
    }
}
