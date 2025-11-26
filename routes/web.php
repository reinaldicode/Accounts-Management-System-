<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserSystemAccessController;

/*
|--------------------------------------------------------------------------
| Web Routes - AMS (Accounts Management System)
|--------------------------------------------------------------------------
*/

// ============================================
// PUBLIC ROUTES (Guest only)
// ============================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware(['web'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Check if user is logged in
    Route::middleware(['ams.auth'])->group(function () {
        
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        
        // ============================================
        // USER MANAGEMENT
        // ============================================
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
        
        // ============================================
        // SYSTEM MANAGEMENT
        // ============================================
        Route::prefix('systems')->name('systems.')->group(function () {
            Route::get('/', [SystemController::class, 'index'])->name('index');
            Route::get('/create', [SystemController::class, 'create'])->name('create');
            Route::post('/', [SystemController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SystemController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SystemController::class, 'update'])->name('update');
            Route::post('/{id}/regenerate-api-key', [SystemController::class, 'regenerateApiKey'])->name('regenerate-api-key');
        });
        
        // ============================================
        // ROLE MANAGEMENT
        // ============================================
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
            
            // Permissions
            Route::get('/{id}/permissions', [RoleController::class, 'permissions'])->name('permissions');
            Route::post('/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('permissions.update');
        });
        
        // ============================================
        // USER SYSTEM ACCESS MANAGEMENT
        // ============================================
        Route::prefix('access')->name('access.')->group(function () {
            Route::get('/', [UserSystemAccessController::class, 'index'])->name('index');
            Route::get('/create', [UserSystemAccessController::class, 'create'])->name('create');
            Route::post('/', [UserSystemAccessController::class, 'store'])->name('store');
            Route::delete('/{id}', [UserSystemAccessController::class, 'revoke'])->name('revoke');
            Route::post('/{id}/toggle', [UserSystemAccessController::class, 'toggle'])->name('toggle');
        });
        
    });
});

// ============================================
// FALLBACK - 404
// ============================================
Route::fallback(function () {
    return view('errors.404');
});