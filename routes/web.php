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

// === START PBI 17: Laporan Audit ===
Route::prefix('management')->name('management.reports.')->group(function () {
    Route::get('/reports', [\App\Http\Controllers\Management\AuditReportController::class, 'index'])
        ->name('index');
    Route::get('/reports/{report}/preview', [\App\Http\Controllers\Management\AuditReportController::class, 'preview'])
        ->name('preview');
    Route::get('/reports/{report}/pdf', [\App\Http\Controllers\Management\AuditReportController::class, 'pdf'])
        ->name('pdf');
    Route::get('/reports/{report}/print', [\App\Http\Controllers\Management\AuditReportController::class, 'print'])
        ->name('print');
    Route::get('/reports/{report}/excel', [\App\Http\Controllers\Management\AuditReportController::class, 'excel'])
        ->name('excel');
});
// === END PBI 17 ===
