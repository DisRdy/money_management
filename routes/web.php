<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

//Authenticated routes with tenant isolation
Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Category Management (CRUD)
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);

    // Transaction Management (CRUD)
    Route::resource('transactions', \App\Http\Controllers\TransactionController::class);

    //Financial Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    // Family Members Management
    Route::middleware('can:manage-family')->group(function () {
        Route::get('/family', [\App\Http\Controllers\FamilyMemberController::class, 'index'])->name('family.index');
        Route::get('/family/create', [\App\Http\Controllers\FamilyMemberController::class, 'create'])->name('family.create');
        Route::post('/family', [\App\Http\Controllers\FamilyMemberController::class, 'store'])->name('family.store');
        Route::get('/family/{user}', [\App\Http\Controllers\FamilyMemberController::class, 'show'])->name('family.show');
        Route::get('/family/{user}/edit', [\App\Http\Controllers\FamilyMemberController::class, 'edit'])->name('family.edit');
        Route::put('/family/{user}', [\App\Http\Controllers\FamilyMemberController::class, 'update'])->name('family.update');
        Route::delete('/family/{user}', [\App\Http\Controllers\FamilyMemberController::class, 'destroy'])->name('family.destroy');
    });

    // Audit Logs (Owner only - read-only access)
    Route::middleware('can:view-audit-logs')->group(function () {
        Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/{auditLog}', [\App\Http\Controllers\AuditLogController::class, 'show'])->name('audit-logs.show');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        // Personal Settings (all roles)
        Route::get('/personal', [\App\Http\Controllers\SettingsController::class, 'personal'])->name('personal');
        Route::post('/personal/theme', [\App\Http\Controllers\SettingsController::class, 'updateTheme'])->name('personal.theme');

        // Family Settings (Owner only - protected by TenantPolicy in controller)
        Route::get('/family', [\App\Http\Controllers\SettingsController::class, 'family'])->name('family');
        Route::post('/family', [\App\Http\Controllers\SettingsController::class, 'updateFamily'])->name('family.update');
    });
});

require __DIR__ . '/auth.php';
