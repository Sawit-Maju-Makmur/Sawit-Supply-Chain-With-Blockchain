<?php

use App\Http\Controllers\LahanController;
use App\Http\Controllers\LandAreaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SelfAssessmentController;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = EnsureUserHasRole::roleOf($user);

    return match (true) {
        $user->hasRole('sustainability_officer') || $user->hasRole('officer') || $role === 'sustainability_officer' || $role === 'officer' => redirect()->route('officer.dashboard'),
        $user->hasRole('management') || $role === 'management'                                                                               => redirect()->route('management.dashboard'),
        $user->hasRole('eksportir_importir') || $user->hasRole('eksportir') || $role === 'eksportir_importir' || $role === 'eksportir'     => redirect()->route('eksportir.dashboard'),
        $user->hasRole('pemasok') || $role === 'pemasok'                                                                                     => redirect()->route('pemasok.dashboard'),
        default                                                                                                                              => abort(403, 'Akun belum memiliki role.'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/pemasok/dashboard', 'dashboards.pemasok')
        ->middleware('role:pemasok')->name('pemasok.dashboard');

    Route::view('/officer/dashboard', 'dashboards.officer')
        ->middleware('role:sustainability_officer|officer')->name('officer.dashboard');

    Route::view('/management/dashboard', 'dashboards.management')
        ->middleware('role:management')->name('management.dashboard');

    Route::view('/eksportir/dashboard', 'dashboards.eksportir')
        ->middleware('role:eksportir_importir|eksportir')->name('eksportir.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// SCRUM-24: CRUD Data Lahan & Pemetaan Batas
Route::resource('lahan', LahanController::class);
Route::resource('land-areas', LandAreaController::class)->only(['create', 'store']);
Route::resource('land_areas', LandAreaController::class)->only(['create', 'store']);

// SCRUM-25: Self-Assessment Kepatuhan Mandiri
Route::resource('assessments', SelfAssessmentController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy']);

// Auth routes (Laravel Breeze)
require __DIR__.'/auth.php';

// SCRUM-10 / PBI 9: Management Kelola Pemasok
require __DIR__.'/management.php';
