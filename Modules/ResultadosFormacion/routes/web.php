<?php

use Illuminate\Support\Facades\Route;
use Modules\ResultadosFormacion\Http\Controllers\Degree\CertificateController;
use Modules\ResultadosFormacion\Http\Controllers\Degree\CommitteeActController;
use Modules\ResultadosFormacion\Http\Controllers\Degree\DegreeApplicationController;
use Modules\ResultadosFormacion\Http\Controllers\Graduate\GraduateController;
use Modules\ResultadosFormacion\Http\Controllers\Graduate\GraduateSurveyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('can:degrees')->prefix('degrees')->name('degree.')->group(function () {
        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
        Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
        Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
        Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');

        Route::get('/applications', [DegreeApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/create', [DegreeApplicationController::class, 'create'])->name('applications.create');
        Route::post('/applications', [DegreeApplicationController::class, 'store'])->name('applications.store');
        Route::get('/applications/{degreeApplication}', [DegreeApplicationController::class, 'show'])->name('applications.show');
        Route::post('/applications/{degreeApplication}/status', [DegreeApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::get('/applications/{degreeApplication}/acts', [CommitteeActController::class, 'index'])->name('applications.acts.index');
        Route::get('/applications/{degreeApplication}/acts/create', [CommitteeActController::class, 'create'])->name('applications.acts.create');
        Route::post('/applications/{degreeApplication}/acts', [CommitteeActController::class, 'store'])->name('applications.acts.store');
    });

    Route::middleware('can:graduates')->prefix('graduates')->name('graduates.')->group(function () {
        Route::get('/', [GraduateController::class, 'index'])->name('index');
        Route::get('/create', [GraduateController::class, 'create'])->name('create');
        Route::post('/', [GraduateController::class, 'store'])->name('store');
        Route::get('/stats', [GraduateController::class, 'stats'])->name('stats');
        Route::get('/{graduate}', [GraduateController::class, 'show'])->name('show');
        Route::get('/{graduate}/edit', [GraduateController::class, 'edit'])->name('edit');
        Route::put('/{graduate}', [GraduateController::class, 'update'])->name('update');
        Route::get('/{graduate}/surveys/create', [GraduateSurveyController::class, 'create'])->name('surveys.create');
        Route::post('/{graduate}/surveys', [GraduateSurveyController::class, 'store'])->name('surveys.store');
    });
});
