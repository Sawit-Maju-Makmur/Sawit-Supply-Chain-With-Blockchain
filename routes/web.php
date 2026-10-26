<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;

Route::get('/', function () { return redirect()->route('certificates.index'); });

Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
Route::delete('/certificates/{id}', [CertificateController::class, 'destroy'])->name('certificates.destroy');
