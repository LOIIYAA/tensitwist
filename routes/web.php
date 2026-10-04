<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpinnerProjectController;
use App\Http\Controllers\SpinnerQuestionController;
use App\Http\Controllers\SpinnerGameController;

Route::get('/', function () {
    return redirect()->route('dashboard');

});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::resource('projects', SpinnerProjectController::class)
    ->middleware('auth');

Route::get('/projects/{project}/questions', [SpinnerQuestionController::class, 'index'])
    ->middleware('auth')
    ->name('projects.questions.index');

Route::get('/projects/{project}/questions/create', [SpinnerQuestionController::class, 'create'])
    ->middleware('auth')
    ->name('projects.questions.create');

Route::post('/projects/{project}/questions', [SpinnerQuestionController::class, 'store'])
    ->middleware('auth')
    ->name('projects.questions.store');

Route::get('/projects/{project}/questions/{question}/edit', [SpinnerQuestionController::class, 'edit'])
    ->middleware('auth')
    ->name('projects.questions.edit');

Route::put('/projects/{project}/questions/{question}', [SpinnerQuestionController::class, 'update'])
    ->middleware('auth')
    ->name('projects.questions.update');

Route::delete('/projects/{project}/questions/{question}', [SpinnerQuestionController::class, 'destroy'])
    ->middleware('auth')
    ->name('projects.questions.destroy');

Route::get('/play/{project:slug}', [SpinnerGameController::class, 'start'])
    ->name('game.start');

Route::get('/play/session/{session:uuid}/result', [SpinnerGameController::class, 'result'])
    ->name('game.result');

Route::post('/play/{project:slug}/start', [SpinnerGameController::class, 'createSession'])
    ->name('game.session.create');

Route::get('/play/session/{session:uuid}', [SpinnerGameController::class, 'play'])
    ->name('game.play');

Route::get('/play/session/{session:uuid}/next-question', [SpinnerGameController::class, 'nextQuestion'])
    ->name('game.next-question');

Route::post('/play/session/{session:uuid}/answer', [SpinnerGameController::class, 'submitAnswer'])
    ->name('game.submit-answer');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';