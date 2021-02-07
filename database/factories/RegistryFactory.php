<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Device;
use App\Models\Registry;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Registry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'device_id' => Device::factory(),
            'application_id' => Application::factory(),
            'language' => $this->faker->randomElement(config('app.supported_languages')),
        ];
    }
}
