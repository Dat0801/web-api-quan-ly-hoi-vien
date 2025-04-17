<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market>
 */
class MarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'market_code' => fake()->unique()->regexify('TT[0-9]{2}'),
            'market_name' => fake()->unique()->word(),
            'created_at' => now(),
            'updated_at' => now(),  
        ];
    }
}
