<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile — all authenticated users
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::delete('/profile/organizations/{organization}', [ProfileController::class, 'leaveOrganization'])->name('profile.leave-organization');

    // Superadmin only
    Route::middleware('superadmin')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/organizations', [UserController::class, 'addOrganization'])->name('users.organizations.add');
        Route::delete('users/{user}/organizations/{organization}', [UserController::class, 'removeOrganization'])->name('users.organizations.remove');
        Route::resource('organizations', OrganizationController::class);
        Route::post('organizations/{organization}/users', [OrganizationController::class, 'addUser'])->name('organizations.users.add');
        Route::delete('organizations/{organization}/users/{user}', [OrganizationController::class, 'removeUser'])->name('organizations.users.remove');
    });
});
