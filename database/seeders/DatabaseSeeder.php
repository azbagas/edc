<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Assistant;
use App\Models\Diagnose;
use App\Models\Disease;
use App\Models\MedicineType;
use App\Models\Owner;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Treatment;
use App\Models\TreatmentType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function seedFromCSV($filePath, $model) {
        $csvFile = fopen(storage_path($filePath), 'r');
        $columnNames = fgetcsv($csvFile, 2000, ",");
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            $dataInput = array_combine($columnNames, $data);

            // Loop untuk mengganti nilai kosong dengan null
            foreach ($dataInput as $key => &$value) {
                if ($value === '') {
                    $value = null;
                }
            }

            $model::create($dataInput);
        }
        fclose($csvFile);
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // roles
        $roles = ['owner', 'admin', 'doctor'];
        foreach ($roles as $role) {
            Role::create([
                'name' => $role
            ]);
        }

        $this->seedFromCSV('app/seeder-klinik/users.csv', User::class);
        $this->seedFromCSV('app/seeder-klinik/owners.csv', Owner::class);
        $this->seedFromCSV('app/seeder-klinik/admins.csv', Admin::class);
        $this->seedFromCSV('app/seeder-klinik/doctors.csv', Doctor::class);
        $this->seedFromCSV('app/seeder-klinik/assistants.csv', Assistant::class);
        $this->seedFromCSV('app/seeder-klinik/patients.csv', Patient::class);
        
        // appointment
        $appointments = Appointment::factory()->count(20)
                                                ->create();

        function generateRandomArrayNumberUnique($maxNumber, $minNumber = 1) {
            // Function for generate array of integer uniquely
            // with 70%-90% amount
            $array = [];
            $countNumber = mt_rand(1, 5);
            for ($i=0; $i < $countNumber; $i++) {
                do {
                    $number = mt_rand($minNumber, $maxNumber);
                } while (in_array($number, $array));
                array_push($array, $number);
            }
            return $array;
        }
        
        $this->seedFromCSV('app/seeder-klinik/medicine_types.csv', MedicineType::class);
        $this->seedFromCSV('app/seeder-klinik/medicines.csv', Medicine::class);

        // appointment medicine
        $randomAppointments = $appointments->random(6);

        foreach ($randomAppointments as $appointment) {
            $medicineIDs = generateRandomArrayNumberUnique(Medicine::all()->count());
            foreach ($medicineIDs as $medicineID) {
                $appointment->medicines()
                                ->attach($medicineID, 
                                            ['price' => Medicine::find($medicineID)->price, 
                                            'quantity' => mt_rand(1, 5)]);
            }
        }

        $this->seedFromCSV('app/seeder-klinik/treatment_types.csv', TreatmentType::class);
        $this->seedFromCSV('app/seeder-klinik/treatments.csv', Treatment::class);
        
        // appointment treatment
        foreach ($appointments as $appointment) {
            $treatmentIDs = generateRandomArrayNumberUnique(Treatment::all()->count());
            foreach ($treatmentIDs as $treatmentID) {
                $appointment->treatments()
                            ->attach($treatmentID,
                                        ['price' => fake()->randomElement([50000, 80000, 150000, 250000]),
                                         'note' => fake()->sentence()]);
            }
        }
        
        $this->seedFromCSV('app/seeder-klinik/diseases.csv', Disease::class);
        $this->seedFromCSV('app/seeder-klinik/diagnoses.csv', Diagnose::class);
        
        // appointment diagnose
        foreach ($appointments as $appointment) {
            $diagnoseIDs = generateRandomArrayNumberUnique(Diagnose::all()->count());
            foreach ($diagnoseIDs as $diagnoseID) {
                $appointment->diagnoses()
                            ->attach($diagnoseID,
                                        ['note' => fake()->randomElement(['', fake()->sentence()])]);
            }
        }
        
        $this->seedFromCSV('app/seeder-klinik/payment_types.csv', PaymentType::class);
        
        // payment
        foreach ($appointments as $appointment) {
            $amount = 0;
            foreach ($appointment->medicines as $medicine) {
                $amount += $medicine->pivot->price * $medicine->pivot->quantity;
            }

            foreach ($appointment->treatments as $treatment) {
                $amount += $treatment->pivot->price;
            }

            Payment::factory()->create([
                'appointment_id' => $appointment->id,
                'amount' => $amount,
                'doctor_percentage' => $appointment->doctor->doctor_percentage
            ]);
        }
    }
}
