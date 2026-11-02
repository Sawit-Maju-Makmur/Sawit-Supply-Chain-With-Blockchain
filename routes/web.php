<?php

<<<<<<< HEAD
use App\Http\Controllers\SelfAssessmentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/assessments');

Route::resource('assessments', SelfAssessmentController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy']);
=======
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\LandAreaController;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match (true) {
        $user->hasRole('sustainability_officer') => redirect()->route('officer.dashboard'),
        $user->hasRole('management')             => redirect()->route('management.dashboard'),
        $user->hasRole('eksportir_importir')     => redirect()->route('eksportir.dashboard'),
        $user->hasRole('pemasok')                => redirect()->route('pemasok.dashboard'),
        default                                  => abort(403, 'Akun belum memiliki role.'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/pemasok/dashboard', 'dashboards.pemasok')
        ->middleware('role:pemasok')->name('pemasok.dashboard');

    Route::view('/officer/dashboard', 'dashboards.officer')
        ->middleware('role:sustainability_officer')->name('officer.dashboard');

    Route::view('/management/dashboard', 'dashboards.management')
        ->middleware('role:management')->name('management.dashboard');

    Route::view('/eksportir/dashboard', 'dashboards.eksportir')
        ->middleware('role:eksportir_importir')->name('eksportir.dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::resource('land-areas', LandAreaController::class)->only(['create', 'store']);
Route::resource('land_areas', LandAreaController::class)->only(['create', 'store']);
Route::resource('lahan', LahanController::class);

>>>>>>> f017a484a6c468e69098875f0dd98e4f15c46ac3
