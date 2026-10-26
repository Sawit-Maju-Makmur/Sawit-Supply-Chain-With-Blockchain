<?php

use App\Http\Controllers\SelfAssessmentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/assessments');

Route::resource('assessments', SelfAssessmentController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy']);
