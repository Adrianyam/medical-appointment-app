<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Rutas de Doctores (solo lectura y edición, sin crear nuevos)
    Route::resource('doctors', App\Http\Controllers\Admin\DoctorController::class)
        ->except(['create', 'store']);

    // Rutas para editar horarios de doctores
    Route::get('doctors/{doctor}/schedule', [App\Http\Controllers\Admin\DoctorController::class, 'editSchedule'])
        ->name('doctors.schedule.edit');
    Route::put('doctors/{doctor}/schedule', [App\Http\Controllers\Admin\DoctorController::class, 'updateSchedule'])
        ->name('doctors.schedule.update');

    // Rutas de Citas Médicas
    Route::resource('appointments', App\Http\Controllers\Admin\AppointmentController::class);
    
    // Ruta para la consulta médica
    Route::get('/appointments/{appointment}/consultation', [App\Http\Controllers\Admin\AppointmentController::class, 'consultation'])
        ->name('appointments.consultation');

});
