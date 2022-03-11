<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function($router){
    Route::prefix('auth')->group(function(){
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });

    Route::get('user/@{username}', [UserController::class, 'showUser']);
    Route::get('user/@{username}/activity', [UserController::class, 'getActivity']);

    Route::get('forums/tag/{tag}', [ForumController::class, 'filterTag']);
    Route::apiResource('forums', ForumController::class);
    Route::apiResource('forums.comments', CommentsController::class);
;});
