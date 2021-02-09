<?php

namespace App\Providers;

use App\Models\Device;
use App\Services\SubscriptionProviders\AppleStoreService;
use App\Services\SubscriptionProviders\GooglePlayService;
use App\Services\SubscriptionProviders\SubscriptionProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SubscriptionProvider::class, function ($app, array $params) {
            [$os, $application] = $params;
            switch ($os) {
                case Device::OS_IOS:
                    $provider_name = 'Apple';
                    $service = AppleStoreService::class;
                    break;
                case Device::OS_ANDROID:
                    $provider_name = 'Google';
                    $service = GooglePlayService::class;
                    break;
                default:
                    throw new \Exception('The requested service provider is not supported');
            }

            $endpoint_url = $this->app['config']['services']['subscription_providers'][$provider_name]['endpoint_url'];
            return new $service($endpoint_url, $application->{strtolower($provider_name) . '_api_credentials'});
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
