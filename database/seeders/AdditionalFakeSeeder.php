<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdditionalFakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = DB::table('patients')->get();
        foreach ($patients as $patient) {

            DB::table('patients')
                ->where('id', $patient->id)
                ->update([
                    'date_of_birth' => fake()->date(max:'2017-12-12'),
                    'phone' => fake()->phoneNumber()
                ]);
        }
    }
}
