<?php

namespace Database\Factories;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dataset>
 */
class DatasetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fintech_app_id' => \App\Models\FintechApp::factory(),
            'name' => $this->faker->words(3, true) . ' Dataset',
            'source' => $this->faker->randomElement(['Google Play', 'App Store', 'Twitter']),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'failed']),
            'record_count' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
