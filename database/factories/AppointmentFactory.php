<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Assistant;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => fake()->numberBetween(1, Doctor::all()->count()),
            'admin_id' => fake()->numberBetween(1, Admin::all()->count()),
            'assistant_id' => fake()->numberBetween(1, Assistant::all()->count()),
            'patient_id' => Patient::pluck('id')->random(),
            'complaint' => fake()->sentence(),
            'date' => fake()->dateTimeBetween('-1 month', 'now'),
            'next_appointment_date' => fake()->dateTimeBetween('+1 day', '+1 month'),
            'status' => fake()->randomElement(['Menunggu', 'Diperiksa', 'Selesai'])
        ];
    }
}
