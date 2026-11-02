<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\LandAreaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SelfAssessmentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/assessments');

Route::resource('assessments', SelfAssessmentController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy']);

Route::resource('lahan', LahanController::class);
Route::resource('land-areas', LandAreaController::class)->only(['create', 'store']);

Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
Route::delete('/certificates/{id}', [CertificateController::class, 'destroy'])->name('certificates.destroy');

Route::view('/dashboard', 'dashboard')->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
