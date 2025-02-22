<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::domain("{tenant}.api.test")->group(function () {
//    Route::get("/test", function ($tenant) {
//        dump($tenant);
//    });
//});
