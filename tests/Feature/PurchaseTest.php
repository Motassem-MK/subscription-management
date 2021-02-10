<?php

namespace Tests\Feature;

use App\Models\Registry;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Feature\Traits\MocksExternalEndpoints;

class PurchaseTest extends TestCase
{
    use RefreshDatabase, MocksExternalEndpoints;

    private $endpoint = '/api/subscriptions/purchase';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockExternalEndpoints();
    }

    /**
     * @test
     */
    public function it_should_make_a_purchase_on_valid_token_and_valid_receipt()
    {
        $registry = Registry::factory()->create();
        $client_token = $registry->client_token;
        $valid_receipt = $this->generateValidReceipt();
        $inputs = [
            'client-token' => $client_token,
            'receipt' => $valid_receipt
        ];

        $response = $this->postJson($this->endpoint, $inputs);

        $response->assertJsonStructure(['expiration_date']);
        $this->assertDatabaseHas('subscriptions', [
            'registry_id' => (string)$registry->id,
            'receipt' => $valid_receipt,
            'status' => Subscription::STATUS_STARTED,
            'expiration_date' => Carbon::parse($response['expiration_date'])
        ]);
        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_should_not_make_purchase_on_valid_token_and_invalid_receipt()
    {
        $client_token = Registry::factory()->create()['client_token'];
        $invalid_receipt = $this->generateInvalidReceipt();
        $inputs = [
            'client-token' => $client_token,
            'receipt' => $invalid_receipt
        ];

        $response = $this->postJson($this->endpoint, $inputs);

        $this->assertDatabaseCount('subscriptions', 0);
        $response->assertJsonMissing(['expiration_date']);
        $response->assertStatus(400);
    }

    /**
     * @test
     */
    public function it_should_not_make_purchase_on_invalid_token_and_valid_receipt()
    {
        $client_token = '00000000000000000000000000000000';
        $inputs = [
            'client-token' => $client_token,
            'receipt' => $this->generateValidReceipt()
        ];

        $response = $this->postJson($this->endpoint, $inputs);

        $this->assertDatabaseCount('subscriptions', 0);
        $response->assertJsonMissing(['expiration_date']);
        $response->assertStatus(422);
    }

    private function generateValidReceipt(): string
    {
        return Str::random(10) . '1';
    }

    private function generateInvalidReceipt(): string
    {
        return Str::random(10) . '2';
    }
}
