<?php

namespace App\Providers;

use App\Events\SubscriptionCanceled;
use App\Events\SubscriptionRenewed;
use App\Events\SubscriptionStarted;
use App\Listeners\SubscriptionStatusChange\SubscriptionCanceledListener;
use App\Listeners\SubscriptionStatusChange\SubscriptionRenewedListener;
use App\Listeners\SubscriptionStatusChange\SubscriptionStartedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SubscriptionStarted::class => [
            SubscriptionStartedListener::class,
        ],
        SubscriptionRenewed::class => [
            SubscriptionRenewedListener::class,
        ],
        SubscriptionCanceled::class => [
            SubscriptionCanceledListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
