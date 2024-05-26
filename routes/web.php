<?php

use App\Models\User;
use App\Models\Event;
use App\Models\Monitoring;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Queue\Monitor;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Basic Routing
Route::get('/', function () {
    return redirect('/login');
});

//Named Routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/logs', function () {
    $logs = ActivityLog::orderBy('created_at', 'desc')->get();
    return view('logs')->with(compact('logs'));
})->middleware(['auth', 'verified'])->name('logs');

//Grouped Routes with router resource
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::post('/user/create', [UserController::class, 'store'])->name('user.store');
    Route::get('/users', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('users');
    Route::get('/users/{user:id}', [UserController::class, 'get_user'])->middleware(['auth', 'verified'])->name('users.get');
    Route::put('/users/{user:id}/update', [UserController::class, 'update_user'])->middleware(['auth', 'verified'])->name('user.update');
    Route::delete('/users/{user:id}/destroy', [StudentController::class, 'destroy_user'])->name('user.destroy');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/generateReports', [ReportController::class, 'generateReport'])->name('generate.report');
    Route::get('/reports', [ReportController::class, 'index'])->middleware(['auth', 'verified'])->name('reports');
    Route::get('/reports/export', [ReportController::class, 'export'])->middleware(['auth', 'verified'])->name('reports.export');
    
    Route::post('/monitoring/create', [MonitoringController::class, 'store'])->name('monitoring.store');
    Route::post('/monitoring/student/create', [MonitoringController::class, 'student_store'])->name('monitoring.student.store');
    Route::get('/monitoring', [MonitoringController::class, 'get_events_and_monitoring'])->middleware(['auth', 'verified'])->name('monitoring');
    Route::post('/monitoring/destroy', [MonitoringController::class, 'destroy_monitoring'])->name('monitoring.destroy');
    
    Route::get('/students', [StudentController::class, 'show_students'])->middleware(['auth', 'verified'])->name('students');
    Route::post('/student/create', [StudentController::class, 'store'])->name('student.store');
    // Route-Model Binding
    Route::get('/student/{student:id}', [StudentController::class, 'get_student'])->name('student.get');
    Route::put('/student/{id}/update', [StudentController::class, 'update_student'])->name('student.update');
    Route::delete('/student/{student:id}/destroy', [StudentController::class, 'destroy_student'])->name('student.destroy');
    
    Route::get('/events', [EventController::class, 'show_events'])->middleware(['auth', 'verified'])->name('events');
    Route::post('/event/create', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/{event:id}', [EventController::class, 'get_event'])->name('event.get');
    Route::get('/events/get', [EventController::class, 'show_all'])->name('events.show_all');
    Route::put('/event/{id}/update', [EventController::class, 'update_event'])->name('event.update');
    Route::delete('/event/{event:id}/destroy', [EventController::class, 'destroy_event'])->name('event.destroy');

});

require __DIR__.'/auth.php';