<?php

namespace Database\Factories;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_type_id' => fake()->numberBetween(1, PaymentType::all()->count()),
            'operational_cost' => fake()->randomElement([0, 20000, 50000, 75000]),
            'note' => fake()->randomElement(['', fake()->sentence()]),
            'status' => 'Lunas'
            // 'status' => fake()->randomElement(['Lunas', 'Belum lunas'])
        ];
    }
}
