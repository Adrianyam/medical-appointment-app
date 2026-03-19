<?php

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
