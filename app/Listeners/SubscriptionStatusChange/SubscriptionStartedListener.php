<?php

namespace App\Listeners\SubscriptionStatusChange;

use App\Events\SubscriptionStarted;

class SubscriptionStartedListener extends BaseSubscriptionStatusChangeListener
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
     * @param  SubscriptionStarted  $event
     * @return void
     */
    public function handle(SubscriptionStarted $event)
    {
        $this->reportToThirdParty(self::EVENT_STARTED, $event->subscription);
    }
}
