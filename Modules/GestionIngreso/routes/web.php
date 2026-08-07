<?php

use Illuminate\Support\Facades\Route;
use Modules\GestionIngreso\Http\Controllers\Admission\AdmissionProcessController;
use Modules\GestionIngreso\Http\Controllers\Admission\ApplicantController;
use Modules\GestionIngreso\Http\Controllers\Enrollment\EnrollmentController;
use Modules\GestionIngreso\Http\Controllers\Enrollment\ReportController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('can:coordinador-admision')->prefix('admission')->name('admission.')->group(function () {
        Route::resource('processes', AdmissionProcessController::class);
        Route::post('/processes/{process}/finalize', [AdmissionProcessController::class, 'finalize'])->name('processes.finalize');
        Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants.index');
        Route::get('/applicants/create', [ApplicantController::class, 'create'])->name('applicants.create');
        Route::post('/applicants', [ApplicantController::class, 'store'])->name('applicants.store');
        Route::get('/applicants/{applicant}', [ApplicantController::class, 'show'])->name('applicants.show');
        Route::post('/applicants/{applicant}/result', [ApplicantController::class, 'saveResult'])->name('applicants.result');
        Route::get('/applicants/{applicant}/constancia', [ApplicantController::class, 'constancia'])->name('applicants.constancia');
    });

    Route::middleware('can:personal-matricula')->prefix('enrollment')->name('enrollment.')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('index');
        Route::get('/create', [EnrollmentController::class, 'create'])->name('create');
        Route::post('/', [EnrollmentController::class, 'store'])->name('store');
        Route::get('/subjects', [EnrollmentController::class, 'subjects'])->name('subjects');
        Route::get('/padron-virtual', [EnrollmentController::class, 'padron'])->name('padron');
        Route::get('/{enrollment}', [EnrollmentController::class, 'show'])->name('show');
        Route::get('/{enrollment}/ficha', [EnrollmentController::class, 'ficha'])->name('ficha');
        Route::get('/{enrollment}/orden-pago', [EnrollmentController::class, 'ordenPago'])->name('orden-pago');
        Route::post('/payments/{paymentOrder}/register', [EnrollmentController::class, 'registerPayment'])->name('payments.register');
        Route::get('/reports/egresados', [ReportController::class, 'egresados'])->name('reports.egresados');
        Route::get('/reports/cronograma', [ReportController::class, 'cronograma'])->name('reports.cronograma');
    });
});
