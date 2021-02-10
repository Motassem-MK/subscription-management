<?php

namespace App\Listeners\SubscriptionStatusChange;

use App\Events\SubscriptionCanceled;

class SubscriptionCanceledListener extends BaseSubscriptionStatusChangeListener
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
     * @param  SubscriptionCanceled  $event
     * @return void
     */
    public function handle(SubscriptionCanceled $event)
    {
        $this->reportToThirdParty(self::EVENT_CANCELED, $event->subscription);
    }
}
