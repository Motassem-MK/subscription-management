<?php

namespace Tests\Feature;

use App\Models\Registry;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\MocksExternalEndpoints;

class SubscriptionCheckTest extends TestCase
{
    use RefreshDatabase, MocksExternalEndpoints;

    private $endpoint = '/api/subscriptions/check-status';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockExternalEndpoints();
    }

    /**
     * @test
     */
        public function it_should_return_valid_status_on_valid_subscription()
    {
        $subscription = Subscription::factory()->create();
        $client_token = $subscription->registry->client_token;
        $inputs = [
            'client-token' => $client_token,
        ];

        $response = $this->postJson($this->endpoint, $inputs);

        $response->assertJson(['status' => 'valid', 'expiration_date' => $subscription->expiration_date->jsonSerialize()]);
        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_should_return_invalid_status_on_expired_subscription()
    {
        $subscription = Subscription::factory()->expired()->create();
        $client_token = $subscription->registry->client_token;
        $inputs = [
            'client-token' => $client_token,
        ];

        $response = $this->postJson($this->endpoint, $inputs);

        $response->assertJson(['status' => 'invalid']);
        $response->assertOk();
    }


    /**
     * @test
     */
    public function it_should_return_invalid_status_on_no_subscription()
    {
        $client_token = Registry::factory()->create()['client_token'];
        $inputs = [
            'client-token' => $client_token,
        ];

        $response = $this->postJson($this->endpoint, $inputs);

        $response->assertJson(['status' => 'invalid']);
        $response->assertOk();
    }
}
