<?php
namespace Tests\Traits;

use Illuminate\Support\Facades\Http;

trait MocksExternalEndpoints {
    public function mockExternalEndpoints() {
        Http::fake([
            config('services.subscription_providers.Google.endpoint_url') => function ($request) {
                return $this->mockSubscriptionProviderAPI($request);
            },
            config('services.subscription_providers.Apple.endpoint_url') => function ($request) {
                return $this->mockSubscriptionProviderAPI($request);
            },
            '*' => function ($request) {
                return $this->mockThirdPartyAPI($request);
            }
        ]);
    }

    private function mockSubscriptionProviderAPI($request)
    {
        if ($this->checkReceiptIsValid($request['receipt'])) {
            return Http::response(['status' => 'success', 'expiration_date' => '2021-06-10 20:00:00'], 200);
        }
        return Http::response(['status' => 'failed'], 400);
    }

    private function mockThirdPartyAPI($request)
    {
        $status = [200, 400][random_int(0, 1)];
        return Http::response(null, $status);
    }

    private function checkReceiptIsValid(string $receipt): bool
    {
        $last_character = (int)substr($receipt, -1);
        return $last_character % 2 != 0;
    }
}
