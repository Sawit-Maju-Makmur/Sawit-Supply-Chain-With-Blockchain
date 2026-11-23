<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SelfAssessmentController;
use App\Http\Controllers\Supplier\SupplierDocumentController;
use App\Http\Controllers\Supplier\SupplierProfileController;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

// Profil akun: cukup login, agar user yang belum verifikasi tetap bisa mengubah emailnya.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Satu-satunya definisi /dashboard: arahkan sesuai role.
    Route::get('/dashboard', function () {
        return match (EnsureUserHasRole::roleOf(auth()->user())) {
            'sustainability_officer' => redirect()->route('officer.dashboard'),
            'management'             => redirect()->route('management.dashboard'),
            'eksportir_importir'     => redirect()->route('eksportir.dashboard'),
            'pemasok'                => redirect()->route('pemasok.dashboard'),
            default                  => abort(403, 'Akun belum memiliki role.'),
        };
    })->name('dashboard');

    Route::get('/officer/dashboard', [DashboardController::class, 'officer'])
        ->middleware('role:sustainability_officer')->name('officer.dashboard');

    Route::get('/management/dashboard', [DashboardController::class, 'management'])
        ->middleware('role:management')->name('management.dashboard');

    Route::get('/eksportir/dashboard', [DashboardController::class, 'exporter'])
        ->middleware('role:eksportir_importir')->name('eksportir.dashboard');

    // Semua fitur pemasok ada di sini, dan hanya di sini.
    Route::middleware('role:pemasok')->group(function () {
        Route::get('/pemasok/dashboard', [DashboardController::class, 'supplier'])->name('pemasok.dashboard');

        Route::get('/pemasok/profil-usaha', [SupplierProfileController::class, 'edit'])->name('pemasok.profile.edit');
        Route::put('/pemasok/profil-usaha', [SupplierProfileController::class, 'update'])->name('pemasok.profile.update');

        Route::resource('lahan', LahanController::class);

        Route::get('/pemasok/documents', [SupplierDocumentController::class, 'index'])->name('pemasok.documents.index');
        Route::post('/pemasok/documents/{lahan}', [SupplierDocumentController::class, 'store'])->name('pemasok.documents.store');
        Route::get('/pemasok/documents/{document}/file', [SupplierDocumentController::class, 'download'])->name('pemasok.documents.download');
        Route::delete('/pemasok/documents/{document}', [SupplierDocumentController::class, 'destroy'])
            ->name('pemasok.documents.destroy');

        Route::get('/pemasok/self-assessment', [SelfAssessmentController::class, 'index'])->name('assessments.index');
        Route::get('/pemasok/self-assessment/create', [SelfAssessmentController::class, 'create'])->name('assessments.create');
        Route::post('/pemasok/self-assessment', [SelfAssessmentController::class, 'store'])->name('assessments.store');
        Route::get('/pemasok/self-assessment/{assessment}', [SelfAssessmentController::class, 'show'])->name('assessments.show');
        Route::delete('/pemasok/self-assessment/{assessment}', [SelfAssessmentController::class, 'destroy'])->name('assessments.destroy');
    });
});

require __DIR__.'/auth.php';
require __DIR__.'/management.php';