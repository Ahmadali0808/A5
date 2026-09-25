<?php

namespace Database\Factories;

use App\Models\Band;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Band>
 */
class BandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
       public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'biography' => fake()->paragraph(),
            'photo' => null,
            'background_color' => '#ffffff',
            'text_color' => '#000000',
        ];
    }
}
