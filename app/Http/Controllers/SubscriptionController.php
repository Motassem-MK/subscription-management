<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subscription\ShowRequest;
use App\Http\Requests\Subscription\StoreRequest;
use App\Models\Registry;
use App\Models\Subscription;
use App\Services\SubscriptionProviders\SubscriptionProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $registry = Registry::getByClientToken($request->input('client-token'));

        $subscription_service = App::make(SubscriptionProvider::class, [$registry->device->os, $registry->application]);
        $verification_result = $subscription_service->verifyReceipt($request->input('receipt'));

        if ($verification_result['status'] != 'success') {
            return response()->json('invalid receipt', 400);
        }

        $registry->addStartedSubscription([
            'receipt' => $request->input('receipt'),
            'expiration_date' => Carbon::parse(
                $verification_result['expiration_date'],
                $subscription_service->getProviderTimeZone()
            )->setTimezone('UTC')
        ]);

        return response()->json(['expiration_date' => $registry->subscription()->expiration_date]);
    }

    /**
     * Display the specified resource.
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $subscription = Registry::getByClientToken($request->input('client-token'))->subscription();

        if (!$subscription || !$subscription->isValid) {
            return response()->json(['status' => 'invalid']);
        }

        return response()->json(['status' => 'valid', 'expiration_date' => $subscription->expiration_date]);
    }
}
