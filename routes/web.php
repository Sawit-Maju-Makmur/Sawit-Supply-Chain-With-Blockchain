<?php

use App\Http\Controllers\LahanController;
use App\Http\Controllers\LandAreaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SelfAssessmentController;
use App\Http\Controllers\Supplier\SupplierDocumentController;
use App\Http\Controllers\Supplier\SupplierProfileController;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = EnsureUserHasRole::roleOf($user);

    return match (true) {
        $user->hasRole('sustainability_officer') || $role === 'sustainability_officer' => redirect()->route('officer.dashboard'),
        $user->hasRole('management') || $role === 'management'                         => redirect()->route('management.dashboard'),
        $user->hasRole('eksportir_importir') || $role === 'eksportir_importir'         => redirect()->route('eksportir.dashboard'),
        $user->hasRole('pemasok') || $role === 'pemasok'                               => redirect()->route('pemasok.dashboard'),
        default                                                                        => abort(403, 'Akun belum memiliki role.'),
    };
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/pemasok/dashboard', [DashboardController::class, 'supplier'])
        ->middleware('role:pemasok')->name('pemasok.dashboard');

    Route::get('/officer/dashboard', [DashboardController::class, 'officer'])
        ->middleware('role:sustainability_officer')->name('officer.dashboard');

    Route::get('/management/dashboard', [DashboardController::class, 'management'])
        ->middleware('role:management')->name('management.dashboard');

    Route::get('/eksportir/dashboard', [DashboardController::class, 'exporter'])
        ->middleware('role:eksportir_importir')->name('eksportir.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:pemasok'])->group(function () {
    Route::get('/pemasok/profil-usaha', [SupplierProfileController::class, 'edit'])->name('pemasok.profile.edit');
    Route::put('/pemasok/profil-usaha', [SupplierProfileController::class, 'update'])->name('pemasok.profile.update');
    Route::resource('lahan', LahanController::class);
    Route::get('/pemasok/documents', [SupplierDocumentController::class, 'index'])->name('pemasok.documents.index');
    Route::post('/pemasok/documents/{lahan}', [SupplierDocumentController::class, 'store'])->name('pemasok.documents.store');
    Route::get('/pemasok/documents/{document}/file', [SupplierDocumentController::class, 'download'])->name('pemasok.documents.download');
    Route::get('/pemasok/self-assessment', [SelfAssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/pemasok/self-assessment/create', [SelfAssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/pemasok/self-assessment', [SelfAssessmentController::class, 'store'])->name('assessments.store');
    Route::get('/pemasok/self-assessment/{assessment}', [SelfAssessmentController::class, 'show'])->name('assessments.show');
    Route::delete('/pemasok/self-assessment/{assessment}', [SelfAssessmentController::class, 'destroy'])->name('assessments.destroy');
});

Route::resource('land-areas', LandAreaController::class)->only(['create', 'store']);

require __DIR__.'/auth.php';

require __DIR__.'/management.php';

