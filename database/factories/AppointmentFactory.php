<?php

namespace Database\Factories;

use App\Models\Assistant;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
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
            'admin_id' => fake()->numberBetween(3, 4),
            'assistant_id' => fake()->numberBetween(1, Assistant::all()->count()),
            'patient_id' => Patient::pluck('id')->random(),
            'complaint' => fake()->sentence(),
            'date_time' => fake()->dateTimeBetween('-2 month', 'now'),
            'next_appointment_date_time' => fake()->optional()->dateTimeBetween('+1 day', '+1 month'),
            'status_id' => 3
        ];
    }
}
