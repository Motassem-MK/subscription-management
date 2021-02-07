<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Device;
use App\Models\Registry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_should_register_an_ios_device_with_existing_application()
    {
        $application = Application::factory()->create(['appId' => 'com.teknasyon.appmoni']);
        $inputs = [
            'uID' => 'EA7583CD-A667-48BC-B806-42ECB2B48606',
            'appID' => $application->appID,
            'language' => config('app.supported_languages')[0],
            'os' => Device::OS_IOS
        ];

        $response = $this->postJson('/api/register', $inputs);

        $this->assertDatabaseHas('devices', [
            'uID' => $inputs['uID'],
            'os' => $inputs['os']
        ]);
        $this->assertDatabaseHas('registries', [
            'application_id' => $application->id,
            'language' => $inputs['language']
        ]);
        $this->assertEquals(32, strlen($response['client-token']));
        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_should_return_existing_token_when_receiving_existing_registry_uid_and_app_id()
    {
        $registry = Registry::factory()->create();
        $inputs = [
            'uID' => $registry->device->uID,
            'appID' => $registry->application->appID,
            'language' => $registry->language,
            'os' => $registry->device->os
        ];

        $response = $this->postJson('/api/register', $inputs);

        $this->assertEquals($registry->client_token, $response['client-token']);
        $response->assertOk();
    }

    /**
     * @test
     */
    public function it_should_create_new_registry_and_return_different_token_when_receiving_existing_registered_device_with_different_app_id()
    {
        $registry = Registry::factory()->create();
        $application = Application::factory()->create();
        $inputs = [
            'uID' => $registry->device->uID,
            'appID' => $application->appID,
            'language' => $registry->language,
            'os' => $registry->device->os
        ];

        $response = $this->postJson('/api/register', $inputs);

        $this->assertDatabaseCount('registries', 2);
        $this->assertNotEquals($registry->client_token, $response['client-token']);
        $response->assertOk();
    }
}
