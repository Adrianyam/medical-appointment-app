<?php

use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
}) ->name('dashboard');

//gestion de roles
Route::resource('roles', RoleController::class);
//gestion de usuarios
Route::resource('users', UserController::class); //controlaor de usuarios
//gestion de doctores
Route::resource('doctors', DoctorController::class);
Route::get('doctors/{doctor}/schedule', [DoctorController::class, 'editSchedule'])
    ->name('doctors.schedule.edit');
Route::put('doctors/{doctor}/schedule', [DoctorController::class, 'updateSchedule'])
    ->name('doctors.schedule.update');
// Editar información de doctor directamente desde usuario (con o sin doctor registrado)
Route::get('users/{user}/edit-doctor', [DoctorController::class, 'editDoctorInfo'])
    ->name('users.edit-doctor');
Route::put('users/{user}/update-doctor', [DoctorController::class, 'updateDoctorInfo'])
    ->name('users.update-doctor');
//gestion de pacientes
Route::resource('patients', PatientController::class); //controlaor de pacientes
//gestion de citas
Route::resource('appointments', AppointmentController::class);
Route::get('appointments/{appointment}/receipt', [AppointmentController::class, 'receipt'])
    ->name('appointments.receipt');