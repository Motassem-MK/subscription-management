<?php

namespace App\Listeners\SubscriptionStatusChange;

use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class BaseSubscriptionStatusChangeListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'subscription_change_callbacks';

    const EVENT_STARTED = 'SUBSCRIPTION_STARTED';
    const EVENT_RENEWED = 'SUBSCRIPTION_RENEWED';
    const EVENT_CANCELED = 'SUBSCRIPTION_CANCELED';

    function reportToThirdParty(string $event_name, Subscription $subscription)
    {
        $response = Http::post($subscription->registry->application->third_party_endpoint, [
            'event' => $event_name,
            'appID' => $subscription->registry->application->appID,
            'deviceID' => $subscription->registry->device->uID,
        ]);

        if (!in_array($response->status(), [200, 201])) {
            $this->fail();
        }
    }
}
