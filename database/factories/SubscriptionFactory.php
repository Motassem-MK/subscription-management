<?php

namespace Database\Factories;

use App\Models\Registry;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'registry_id' => Registry::factory(),
            'receipt' => Str::random(5),
            'status' => Subscription::STATUS_STARTED,
            'expiration_date' => $this->faker->dateTimeThisYear
        ];
    }

    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Subscription::STATUS_EXPIRED,
            ];
        });
    }
}
