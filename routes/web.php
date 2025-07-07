<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolePermissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->middleware('can:manage-users')->name('users');
    Route::get('/user/{user}/show', [UserController::class, 'show'])->middleware('can:manage-users')->name('user.show');
    Route::post('/users', [UserController::class, 'store'])->middleware('can:user-create')->name('user.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('can:user-update')->name('user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('can:user-delete')->name('user.destroy');

    Route::resource('role-permission', RolePermissionController::class)
        ->middleware('can:manage-roles')
        ->except(['create', 'edit', 'destroy']); // Jika Anda tidak menggunakan method ini secara langsung
    Route::get('role-permission/{role}', [RolePermissionController::class, 'show'])
        ->middleware('can:manage-roles')
        ->name('role-permission.show');
    Route::put('role-permission/{role}', [RolePermissionController::class, 'update'])
        ->middleware('can:role-update')
        ->name('role-permission.update');
    Route::post('role-permission', [RolePermissionController::class, 'store'])
        ->middleware('can:role-create')
        ->name('role-permission.store');
    Route::post('role-permission/{role}', [RolePermissionController::class, 'destroy'])
        ->middleware('can:role-delete')
        ->name('role.destroy');
    Route::post('role-permission/permission/store', [RolePermissionController::class, 'permissionStore'])
        ->middleware('can:permission-create')
        ->name('permission.store');
    Route::post('role-permission/{id}', [RolePermissionController::class, 'permissionDestroy'])
        ->middleware('can:permission-delete')
        ->name('permission.destroy');



});

