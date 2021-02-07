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
        ];
    }

    public function withAppleStore()
    {
        return $this->state(function (array $attributes) {
            return [
                'apple_api_credentials' => [
                    'username' => $this->faker->userName,
                    'password' => Str::random(5)
                ]
            ];
        });
    }

    public function withGooglePlay()
    {
        return $this->state(function (array $attributes) {
            return [
                'google_api_credentials' => [
                    'username' => $this->faker->userName,
                    'password' => Str::random(5)
                ]
            ];
        });
    }
}
