<?php

use App\Http\Controllers\LandAreaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('land-areas', LandAreaController::class)->only(['create', 'store']);
Route::resource('land_areas', LandAreaController::class)->only(['create', 'store']);
