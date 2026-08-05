<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('ticketflow')->name('ticketflow.')->group(function () {
        Route::inertia('admin', 'admin/dashboard')->name('admin.dashboard');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('events', [\App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
            Route::get('events/create', [\App\Http\Controllers\Admin\EventController::class, 'create'])->name('events.create');
            Route::post('events', [\App\Http\Controllers\Admin\EventController::class, 'store'])->name('events.store');
        });
    });
});

require __DIR__.'/settings.php';
