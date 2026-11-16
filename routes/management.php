<?php

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

