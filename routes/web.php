<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// === START PBI 10 ===
Route::get('/assessment-criteria', [\App\Http\Controllers\AssessmentCriteriaController::class, 'index'])
    ->name('assessment-criteria.index');
Route::post('/assessment-criteria', [\App\Http\Controllers\AssessmentCriteriaController::class, 'store'])
    ->name('assessment-criteria.store');
// === END PBI 10 ===
