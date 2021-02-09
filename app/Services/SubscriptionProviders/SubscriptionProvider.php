<?php

namespace App\Services\SubscriptionProviders;

interface SubscriptionProvider
{
    public function __construct(string $endpoint_url, array $credentials);

    public function verifyReceipt(string $receipt): \Illuminate\Http\Client\Response;

    public function getProviderTimeZone(): string;
}
