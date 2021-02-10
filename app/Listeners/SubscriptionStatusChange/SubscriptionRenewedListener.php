<?php

namespace App\Listeners\SubscriptionStatusChange;

use App\Events\SubscriptionRenewed;

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
