<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;


Route::get('/', function () {
    return "<b> Welcome, PathFinder API V1";
});
Route::get('/maps/{id}',[LocationController::class, 'maps']);

