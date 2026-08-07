<?php

use Modules\GestionCurricular\Http\Controllers\CurriculumReview\ReviewController;
use Modules\GestionCurricular\Http\Controllers\CurriculumReview\TechnicalReportController;
use Modules\GestionCurricular\Http\Controllers\CurriculumReview\ApprovalController;
use Modules\GestionCurricular\Http\Controllers\Syllabus\SyllabusController;
use Modules\GestionCurricular\Http\Controllers\Resource\ResourceRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('can:presidente-cotejo')->prefix('curriculum')->name('curriculum.')->group(function () {
        Route::resource('reviews', ReviewController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::get('/reviews/{review}/evaluate', [ReviewController::class, 'evaluate'])->name('reviews.evaluate');
        Route::post('/reviews/{review}/evaluate', [ReviewController::class, 'saveEvaluation'])->name('reviews.save-evaluation');
        Route::post('/reviews/{review}/complete', [ReviewController::class, 'complete'])->name('reviews.complete');

        Route::get('/reviews/{review}/reports/create', [TechnicalReportController::class, 'create'])->name('reports.create');
        Route::post('/reviews/{review}/reports', [TechnicalReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}', [TechnicalReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/edit', [TechnicalReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [TechnicalReportController::class, 'update'])->name('reports.update');
        Route::post('/reports/{report}/finalize', [TechnicalReportController::class, 'finalize'])->name('reports.finalize');
        Route::get('/reports/{report}/pdf', [TechnicalReportController::class, 'pdf'])->name('reports.pdf');
    });

    Route::middleware('can:director-escuela')->prefix('curriculum')->name('curriculum.')->group(function () {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::get('/approvals/{report}/review', [ApprovalController::class, 'review'])->name('approvals.review');
        Route::post('/approvals/{report}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    });

    Route::middleware('can:syllabi')->prefix('syllabi')->name('syllabi.')->group(function () {
        Route::get('/', [SyllabusController::class, 'index'])->name('index');
        Route::get('/create', [SyllabusController::class, 'create'])->name('create');
        Route::post('/', [SyllabusController::class, 'store'])->name('store');
        Route::get('/subjects', [SyllabusController::class, 'getSubjects'])->name('subjects');
        Route::get('/{syllabus}', [SyllabusController::class, 'show'])->name('show');
        Route::get('/{syllabus}/download', [SyllabusController::class, 'download'])->name('download');
        Route::post('/{syllabus}/visa', [SyllabusController::class, 'visa'])->name('visa');
    });

    Route::middleware('can:resources')->prefix('resources')->name('resources.')->group(function () {
        Route::get('/', [ResourceRequestController::class, 'index'])->name('index');
        Route::get('/create', [ResourceRequestController::class, 'create'])->name('create');
        Route::post('/', [ResourceRequestController::class, 'store'])->name('store');
        Route::get('/{resourceRequest}', [ResourceRequestController::class, 'show'])->name('show');
        Route::post('/{resourceRequest}/response', [ResourceRequestController::class, 'addResponseDocument'])->name('add-response');
        Route::get('/documents/{document}/download', [ResourceRequestController::class, 'downloadDocument'])->name('documents.download');
    });
});
