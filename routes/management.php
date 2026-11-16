<?php

use App\Http\Controllers\Officer\AssessmentCriterionController;
use App\Http\Controllers\Officer\DocumentReviewController;
use App\Http\Controllers\Management\SupplierController;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('management')->name('management.')->group(function () {

    // Hanya Management: export dan ubah status (AC4, AC6, AC7)
    Route::middleware(EnsureUserHasRole::class.':management')->group(function () {
        Route::get('suppliers/export', [SupplierController::class, 'export'])->name('suppliers.export');
        Route::patch('suppliers/{supplier}/status', [SupplierController::class, 'updateStatus'])->name('suppliers.status');
    });

    // Management dan Sustainability Officer (SO hanya melihat)
    Route::middleware(EnsureUserHasRole::class.':management,sustainability_officer')->group(function () {
        Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    });
});

Route::middleware(['auth', 'verified', EnsureUserHasRole::class.':sustainability_officer'])
    ->prefix('officer')
    ->name('officer.')
    ->group(function () {
        Route::get('assessment-criteria', [AssessmentCriterionController::class, 'index'])
            ->name('criteria.index');
        Route::put('assessment-criteria/{category}', [AssessmentCriterionController::class, 'updateCategory'])
            ->whereIn('category', array_keys(\App\Models\AssessmentCriterion::CATEGORIES))
            ->name('criteria.update');
        Route::get('documents', [DocumentReviewController::class, 'index'])
            ->name('documents.index');
        Route::get('documents/{document}/file', [DocumentReviewController::class, 'download'])
            ->name('documents.download');
        Route::patch('documents/{document}/review', [DocumentReviewController::class, 'review'])
            ->name('documents.review');
    });

