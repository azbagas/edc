<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => Carbon::parse(fake()->dateTimeBetween('-2 month', 'now'))->format('d-m-Y'),
            'name' => fake()->sentence(3),
            'amount' => fake()->numberBetween(10, 100) * 1000
        ];
    }
}
