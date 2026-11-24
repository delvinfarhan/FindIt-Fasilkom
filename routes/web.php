<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CivitasReportController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LostReportController;
use App\Http\Controllers\Officer\OfficerCivitasReportController;
use App\Http\Controllers\Officer\OfficerClaimController;
use App\Http\Controllers\Officer\OfficerDashboardController;
use App\Http\Controllers\Officer\OfficerFoundItemController;
use App\Http\Controllers\Officer\OfficerLostReportController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{foundItem}', [CatalogController::class, 'show'])->name('catalog.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// Quick demo login (switch between Nadia Putri & Dedi Saputra)
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Student / Civitas Routes (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications.index');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

    // Lost Reports
    Route::get('/reports', [LostReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [LostReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [LostReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{lostReport}/submitted', [LostReportController::class, 'submitted'])->name('reports.submitted');
    Route::get('/reports/{lostReport}', [LostReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{lostReport}/cancel', [LostReportController::class, 'cancel'])->name('reports.cancel');

    // Claims
    Route::get('/catalog/{foundItem}/claim', [ClaimController::class, 'create'])->name('claims.create');
    Route::post('/catalog/{foundItem}/claim', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');

    // Civitas Found Items (Alur D)
    Route::get('/civitas-reports/create', [CivitasReportController::class, 'create'])->name('civitas-reports.create');
    Route::post('/civitas-reports', [CivitasReportController::class, 'store'])->name('civitas-reports.store');
    Route::get('/civitas-reports/{civitasReport}', [CivitasReportController::class, 'show'])->name('civitas-reports.show');
});

// Officer Security Panel Routes (role:petugas)
Route::prefix('officer')->middleware(['auth', 'role:petugas'])->name('officer.')->group(function () {
    Route::get('/', [OfficerDashboardController::class, 'index'])->name('dashboard');

    // Found Items Management
    Route::get('/items', [OfficerFoundItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [OfficerFoundItemController::class, 'create'])->name('items.create');
    Route::post('/items', [OfficerFoundItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [OfficerFoundItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [OfficerFoundItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [OfficerFoundItemController::class, 'update'])->name('items.update');
    Route::post('/items/{item}/archive', [OfficerFoundItemController::class, 'archive'])->name('items.archive');

    // Lost Reports Management
    Route::get('/reports', [OfficerLostReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [OfficerLostReportController::class, 'show'])->name('reports.show');
    Route::put('/reports/{report}/status', [OfficerLostReportController::class, 'updateStatus'])->name('reports.status');

    // Claims Verification & Handover
    Route::get('/claims', [OfficerClaimController::class, 'index'])->name('claims.index');
    Route::get('/claims/{claim}', [OfficerClaimController::class, 'show'])->name('claims.show');
    Route::post('/claims/{claim}/approve', [OfficerClaimController::class, 'approve'])->name('claims.approve');
    Route::post('/claims/{claim}/reject', [OfficerClaimController::class, 'reject'])->name('claims.reject');
    Route::get('/claims/{claim}/handover', [OfficerClaimController::class, 'showHandover'])->name('claims.handover');
    Route::post('/claims/{claim}/complete-handover', [OfficerClaimController::class, 'completeHandover'])->name('claims.complete-handover');
    Route::get('/claims/{claim}/receipt', [OfficerClaimController::class, 'receipt'])->name('claims.receipt');

    // Civitas Item Reports (Alur D)
    Route::get('/civitas-reports', [OfficerCivitasReportController::class, 'index'])->name('civitas-reports.index');
    Route::get('/civitas-reports/{report}', [OfficerCivitasReportController::class, 'show'])->name('civitas-reports.show');
    Route::post('/civitas-reports/{report}/receive', [OfficerCivitasReportController::class, 'receive'])->name('civitas-reports.receive');
});
