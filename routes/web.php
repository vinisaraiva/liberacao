<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class , 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class , 'login']);
Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/portaria', function () {
    return view('portaria');
})->name('portaria');

Route::middleware('auth')->group(function () {
    Route::get('/api/students/search', [App\Http\Controllers\StudentController::class , 'search']);
    Route::post('/api/release-requests', [App\Http\Controllers\ReleaseRequestController::class , 'store']);
    Route::get('/api/release-requests', [App\Http\Controllers\ReleaseRequestController::class , 'index']);
    Route::post('/api/release-requests/{id}/confirm', [App\Http\Controllers\ReleaseRequestController::class , 'confirm'])->middleware('terminal.auth');

    Route::get('/liberacoes/nova', [App\Http\Controllers\ReleaseRequestController::class , 'create'])->name('releases.create');
    Route::post('/liberacoes/{id}/cancelar', [App\Http\Controllers\ReleaseRequestController::class , 'cancel'])->name('releases.cancel');
    Route::get('/liberacoes/historico', function () {
            return view('releases.history');
        }
        )->name('releases.history');

        // Admin Routes
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/terminals', [App\Http\Controllers\Admin\TerminalController::class , 'index'])->name('terminals.index');
            Route::post('/terminals', [App\Http\Controllers\Admin\TerminalController::class , 'store'])->name('terminals.store');
            Route::put('/terminals/{terminal}', [App\Http\Controllers\Admin\TerminalController::class , 'update'])->name('terminals.update');
            Route::delete('/terminals/{terminal}', [App\Http\Controllers\Admin\TerminalController::class , 'destroy'])->name('terminals.destroy');
            Route::post('/terminals/{terminal}/token', [App\Http\Controllers\Admin\TerminalController::class , 'generateToken'])->name('terminals.token');

            Route::resource('courses', App\Http\Controllers\Admin\CourseController::class)->except(['create', 'edit', 'show']);
            Route::resource('series', App\Http\Controllers\Admin\SerieController::class)->except(['create', 'edit', 'show']);
            Route::resource('turmas', App\Http\Controllers\Admin\TurmaController::class)->except(['create', 'edit', 'show']);
            Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'edit', 'show']);

            Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class , 'index'])->name('reports.index');
            Route::get('/reports/releases', [App\Http\Controllers\Admin\ReportController::class , 'releases'])->name('reports.releases');
            Route::get('/reports/student', [App\Http\Controllers\Admin\ReportController::class , 'student'])->name('reports.student');
        }
        );
    });
