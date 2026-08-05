<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Pricing
        $this->app->singleton(\App\Modules\Pricing\Services\PricingCalculator::class);

        // Payments
        $this->app->bind(
            \App\Modules\Payments\Services\PaymentGatewayInterface::class,
            \App\Modules\Payments\Services\StripeProvider::class
        );

        // Notifications
        $this->app->singleton(\App\Modules\Notifications\Services\NotificationDispatcher::class);

        // Analytics
        $this->app->singleton(\App\Modules\Analytics\Services\AnalyticsQueryService::class);

        // Venue Builder
        $this->app->singleton(\App\Modules\VenueBuilder\Services\VenueBuilderService::class);

        // Events
        $this->app->bind(
            \App\Modules\Events\Repositories\EventRepositoryInterface::class,
            \App\Modules\Events\Repositories\EventRepository::class
        );

        // Tickets
        $this->app->bind(
            \App\Modules\Tickets\Repositories\TicketRepositoryInterface::class,
            \App\Modules\Tickets\Repositories\TicketRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
