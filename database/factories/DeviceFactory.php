<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uID' => sprintf('%s-%s-%s-%s-%s',
                Str::random(8),
                Str::random(4),
                Str::random(4),
                Str::random(4),
                Str::random(12)
            ),
            'os' => $this->faker->randomElement(Device::SUPPORTED_OPERATING_SYSTEMS)
        ];
    }
}
