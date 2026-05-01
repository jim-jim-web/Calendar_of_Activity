<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect the default dashboard to your activity index
Route::get('/dashboard', [ActivityController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // Default Laravel Breeze Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ==========================================
    // ACTIVITY ROUTES
    // ==========================================
    
    // 1. ONLY PIOs can create, edit, update, and delete activities
    Route::middleware('pio')->group(function () {
        Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('/activities/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
        Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
        Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    });

    // 2. EVERYONE logged in can view the activity list
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

    // 3. STUDENTS can mark an activity as completed
    Route::post('/activities/{activity}/complete', [ActivityController::class, 'markCompleted'])->name('activities.complete');

    // 4. Visual Calendar Page
    Route::get('/calendar', [ActivityController::class, 'calendar'])->name('activities.calendar');
});

require __DIR__.'/auth.php';