<?php

use Illuminate\Support\Facades\Route;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Evaluation\EvaluationController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Execution\ClassSessionController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Execution\SubjectExecutionController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Execution\SyllabusSocializationController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Execution\TeacherPerformanceController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Execution\TeachingLoadController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Mobility\AgreementController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Mobility\MobilityController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Research\ResearchProjectController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Tutoring\RemedialProgramController;
use Modules\EnsenanzaAprendizaje\Http\Controllers\Tutoring\TutoringController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('can:evaluations')->prefix('evaluations')->name('evaluations.')->group(function () {
        Route::get('/', [EvaluationController::class, 'index'])->name('index');
        Route::get('/create', [EvaluationController::class, 'create'])->name('create');
        Route::post('/', [EvaluationController::class, 'store'])->name('store');
        Route::get('/record', [EvaluationController::class, 'record'])->name('record');
        Route::get('/acta-pdf', [EvaluationController::class, 'actaPdf'])->name('acta-pdf');
        Route::get('/actas', [EvaluationController::class, 'actas'])->name('actas');
        Route::post('/actas/generar', [EvaluationController::class, 'generarActa'])->name('actas.generar');
        Route::post('/actas/{officialAct}/cerrar', [EvaluationController::class, 'cerrarActa'])->name('actas.cerrar');
        Route::get('/actas/{officialAct}/descargar', [EvaluationController::class, 'downloadAct'])->name('actas.download');
        Route::get('/{evaluation}', [EvaluationController::class, 'show'])->name('show');
    });

    Route::middleware('can:execution')->prefix('execution')->name('execution.')->group(function () {
        Route::get('/loads', [TeachingLoadController::class, 'index'])->name('loads.index');
        Route::get('/loads/create', [TeachingLoadController::class, 'create'])->name('loads.create');
        Route::post('/loads', [TeachingLoadController::class, 'store'])->name('loads.store');
        Route::get('/socializations', [SyllabusSocializationController::class, 'index'])->name('socializations.index');
        Route::get('/socializations/create', [SyllabusSocializationController::class, 'create'])->name('socializations.create');
        Route::post('/socializations', [SyllabusSocializationController::class, 'store'])->name('socializations.store');
        Route::get('/executions', [SubjectExecutionController::class, 'index'])->name('executions.index');
        Route::get('/executions/create', [SubjectExecutionController::class, 'create'])->name('executions.create');
        Route::post('/executions', [SubjectExecutionController::class, 'store'])->name('executions.store');
        Route::post('/executions/{subjectExecution}/close', [SubjectExecutionController::class, 'close'])->name('executions.close');
        Route::get('/performance', [TeacherPerformanceController::class, 'index'])->name('performance.index');
        Route::get('/performance/create', [TeacherPerformanceController::class, 'create'])->name('performance.create');
        Route::post('/performance', [TeacherPerformanceController::class, 'store'])->name('performance.store');
        Route::get('/', [ClassSessionController::class, 'index'])->name('index');
        Route::get('/create', [ClassSessionController::class, 'create'])->name('create');
        Route::post('/', [ClassSessionController::class, 'store'])->name('store');
        Route::get('/coverage', [ClassSessionController::class, 'coverage'])->name('coverage');
        Route::get('/{classSession}', [ClassSessionController::class, 'show'])->name('show');
    });

    Route::middleware('can:tutoring')->prefix('tutoring')->name('tutoring.')->group(function () {
        Route::get('/', [TutoringController::class, 'index'])->name('index');
        Route::get('/create', [TutoringController::class, 'create'])->name('create');
        Route::post('/', [TutoringController::class, 'store'])->name('store');
        Route::get('/remedial', [RemedialProgramController::class, 'index'])->name('remedial.index');
        Route::get('/remedial/create', [RemedialProgramController::class, 'create'])->name('remedial.create');
        Route::post('/remedial', [RemedialProgramController::class, 'store'])->name('remedial.store');
        Route::post('/remedial/{remedialProgram}/status', [RemedialProgramController::class, 'updateStatus'])->name('remedial.status');
        Route::get('/{academicTutoring}', [TutoringController::class, 'show'])->name('show');
        Route::post('/{academicTutoring}/complete', [TutoringController::class, 'complete'])->name('complete');
    });

    Route::middleware('can:mobility')->prefix('mobility')->name('mobility.')->group(function () {
        Route::get('/', [MobilityController::class, 'index'])->name('index');
        Route::get('/create', [MobilityController::class, 'create'])->name('create');
        Route::post('/', [MobilityController::class, 'store'])->name('store');
        Route::get('/agreements', [AgreementController::class, 'index'])->name('agreements.index');
        Route::get('/agreements/create', [AgreementController::class, 'create'])->name('agreements.create');
        Route::post('/agreements', [AgreementController::class, 'store'])->name('agreements.store');
        Route::get('/{mobilityApplication}', [MobilityController::class, 'show'])->name('show');
        Route::post('/{mobilityApplication}/status', [MobilityController::class, 'updateStatus'])->name('status');
    });

    Route::middleware('can:research')->prefix('research')->name('research.')->group(function () {
        Route::get('/', [ResearchProjectController::class, 'index'])->name('index');
        Route::get('/create', [ResearchProjectController::class, 'create'])->name('create');
        Route::post('/', [ResearchProjectController::class, 'store'])->name('store');
        Route::get('/{researchProject}', [ResearchProjectController::class, 'show'])->name('show');
        Route::post('/{researchProject}/status', [ResearchProjectController::class, 'updateStatus'])->name('status');
        Route::get('/{researchProject}/download', [ResearchProjectController::class, 'download'])->name('download');
    });
});
