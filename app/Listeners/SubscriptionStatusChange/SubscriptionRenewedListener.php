<?php

namespace App\Listeners\SubscriptionStatusChange;

use App\Events\SubscriptionRenewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscriptionRenewedListener extends BaseSubscriptionStatusChangeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscriptionRenewed  $event
     * @return void
     */
    public function handle(SubscriptionRenewed $event)
    {
        $this->reportToThirdParty(self::EVENT_RENEWED, $event->subscription);
    }
}
