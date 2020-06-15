<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        // order medicine returned logic
        \App\Events\HasNewReturnedMedicine::class => [
            \App\Listeners\IsLessenPaidQuantityListener::class,
        ],

        // create order event
        \App\Events\NewOrderCreatedEventNotification::class => [
            \App\Listeners\CriticalStockNotification::class,
            \App\Listeners\OutOfStockNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
