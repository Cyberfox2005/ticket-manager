<?php

use App\Modules\Auth\Controllers\Api\AuthController;
use App\Modules\Events\Controllers\Api\EventController;
use App\Modules\Tickets\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Events
        Route::get('/events', [EventController::class, 'index']);
        Route::get('/events/{id}', [EventController::class, 'show']);

        // Tickets
        Route::get('/my-tickets', [TicketController::class, 'myTickets']);
        Route::post('/tickets/validate', [TicketController::class, 'validateTicket']);
        Route::post('/tickets/{id}/transfer', [TicketController::class, 'transfer']);
        Route::post('/tickets/{id}/reissue', [TicketController::class, 'reissue']);
        Route::get('/tickets/{id}/dynamic-qr', [TicketController::class, 'dynamicQr']);

        // Booking
        Route::post('/booking/lock', [\App\Modules\Booking\Controllers\Api\BookingController::class, 'lockSeat']);
        Route::post('/booking/cancel/{id}', [\App\Modules\Booking\Controllers\Api\BookingController::class, 'cancel']);
        Route::get('/booking/quote', [\App\Modules\Booking\Controllers\Api\BookingController::class, 'quote']);

        // Pricing Rules (Admin)
        Route::get('/admin/pricing/rules', [\App\Modules\Pricing\Controllers\Api\PricingController::class, 'index']);

        // Analytics (Admin)
        Route::get('/admin/analytics/revenue', [\App\Modules\Analytics\Controllers\Api\AnalyticsController::class, 'revenue']);

        // Venue Builder (Admin)
        Route::get('/admin/venues/{id}/layout', [\App\Modules\VenueBuilder\Controllers\Api\VenueBuilderController::class, 'getLayout']);
        Route::post('/admin/venues/{id}/layout', [\App\Modules\VenueBuilder\Controllers\Api\VenueBuilderController::class, 'saveLayout']);
    });
});
