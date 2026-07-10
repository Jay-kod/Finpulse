<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dataset_id' => \App\Models\Dataset::factory(),
            'source_id' => $this->faker->uuid(),
            'author_name' => $this->faker->name(),
            'rating' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->paragraph(),
            'processed_status' => $this->faker->randomElement(['pending', 'processed', 'error']),
            'published_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
