<?php

namespace App\Services\SubscriptionProviders;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GooglePlayService implements SubscriptionProvider
{
    private $endpoint_url;
    private $username;
    private $password;

    public function __construct($endpoint_url, array $credentials)
    {
        $this->endpoint_url = $endpoint_url;
        $this->username = $credentials['username'];
        $this->password = $credentials['password'];
    }

    public function verifyReceipt(string $receipt): Response
    {
        return Http::withBasicAuth($this->username, $this->password)
            ->post($this->endpoint_url, [
                'receipt' => $receipt,
            ]);
    }

    public function getProviderTimeZone(): string
    {
        return '0';
    }
}
