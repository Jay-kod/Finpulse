<?php

namespace Database\Factories;

use App\Models\FintechApp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FintechApp>
 */
class FintechAppFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'package_name' => $this->faker->unique()->domainWord() . '.' . $this->faker->domainWord(),
            'playstore_id' => 'com.' . $this->faker->domainWord() . '.' . $this->faker->domainWord(),
            'appstore_id' => (string) $this->faker->numberBetween(100000000, 999999999),
            'platform' => $this->faker->randomElement(['android', 'ios', 'both']),
            'description' => $this->faker->paragraph(),
            'logo_url' => null,
            'is_active' => true,
            'downloads' => $this->faker->numberBetween(1000, 5000000),
            'average_rating' => $this->faker->randomFloat(2, 1.0, 5.0),
        ];
    }
}
