<?php

use Illuminate\Support\Facades\Route;

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

Route::group(["prefix" => "auth"], function () {
    Route::name("login")->post("/login", "Auth\LoginController@login");
});

Route::group(["prefix" => "auth", "middleware" => "auth.api"], function () {
    Route::name("logout")->get("/logout", "Auth\LoginController@logout");
});
