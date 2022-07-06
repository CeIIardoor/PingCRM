<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CollaborateursController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganisationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Organisations

Route::get('organisations', [OrganisationsController::class, 'index'])
    ->name('organisations')
    ->middleware('auth');

Route::get('organisations/create', [OrganisationsController::class, 'create'])
    ->name('organisations.create')
    ->middleware('auth');

Route::post('organisations', [OrganisationsController::class, 'store'])
    ->name('organisations.store')
    ->middleware('auth');

Route::get('organisations/{organisation}/edit', [OrganisationsController::class, 'edit'])
    ->name('organisations.edit')
    ->middleware('auth');

Route::put('organisations/{organisation}', [OrganisationsController::class, 'update'])
    ->name('organisations.update')
    ->middleware('auth');

Route::delete('organisations/{organisation}', [OrganisationsController::class, 'destroy'])
    ->name('organisations.destroy')
    ->middleware('auth');

Route::put('organisations/{organisation}/restore', [OrganisationsController::class, 'restore'])
    ->name('organisations.restore')
    ->middleware('auth');

// Collaborateurs

Route::get('collaborateurs', [CollaborateursController::class, 'index'])
    ->name('collaborateurs')
    ->middleware('auth');

Route::get('collaborateurs/create', [CollaborateursController::class, 'create'])
    ->name('collaborateurs.create')
    ->middleware('auth');

Route::post('collaborateurs', [CollaborateursController::class, 'store'])
    ->name('collaborateurs.store')
    ->middleware('auth');

Route::get('collaborateurs/{collaborateur}/edit', [CollaborateursController::class, 'edit'])
    ->name('collaborateurs.edit')
    ->middleware('auth');

Route::put('collaborateurs/{collaborateur}', [CollaborateursController::class, 'update'])
    ->name('collaborateurs.update')
    ->middleware('auth');

Route::delete('collaborateurs/{collaborateur}', [CollaborateursController::class, 'destroy'])
    ->name('collaborateurs.destroy')
    ->middleware('auth');

Route::put('collaborateurs/{collaborateur}/restore', [CollaborateursController::class, 'restore'])
    ->name('collaborateurs.restore')
    ->middleware('auth');

// Reports

Route::get('reports', [ReportsController::class, 'index'])
    ->name('reports')
    ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
