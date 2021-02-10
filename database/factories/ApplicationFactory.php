<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'appID' => Str::random(36),
            'apple_api_credentials' => [
                'username' => $this->faker->userName,
                'password' => Str::random(5)
            ],
            'google_api_credentials' => [
                'username' => $this->faker->userName,
                'password' => Str::random(5)
            ],
            'third_party_endpoint' => $this->faker->url
        ];
    }

    public function withoutAppleStore()
    {
        return $this->state(function (array $attributes) {
            return [
                'apple_api_credentials' => null
            ];
        });
    }

    public function withoutGooglePlay()
    {
        return $this->state(function (array $attributes) {
            return [
                'google_api_credentials' => null
            ];
        });
    }
}
