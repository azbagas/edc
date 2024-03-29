<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Doctor;
use App\Models\Status;
use App\Models\Disease;
use App\Models\Expense;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Medicine;
use App\Models\Assistant;
use App\Models\Diagnosis;
use App\Models\Treatment;
use App\Models\Appointment;
use App\Models\PaymentType;
use App\Models\MedicineType;
use App\Models\PatientCondition;
use App\Models\TreatmentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

    public function generateRandomArrayNumberUnique($maxNumber, $minNumber = 1) {
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

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedFromCSV('app/initial-data/users.csv', User::class);
        $this->seedFromCSV('app/initial-data/roles.csv', Role::class);
        $this->seedFromCSV('app/initial-data/owners.csv', Owner::class);
        $this->seedFromCSV('app/initial-data/admins.csv', Admin::class);
        $this->seedFromCSV('app/initial-data/doctors.csv', Doctor::class);
        $this->seedFromCSV('app/initial-data/assistants.csv', Assistant::class);
        $this->seedFromCSV('app/initial-data/patients.csv', Patient::class);
        $this->seedFromCSV('app/initial-data/statuses.csv', Status::class);
        
        // role user
        $csvFile = fopen(storage_path('app/initial-data/role_user.csv'), 'r');
        $columnNames = fgetcsv($csvFile, 2000, ",");
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            $dataInput = array_combine($columnNames, $data);

            // Loop untuk mengganti nilai kosong dengan null
            foreach ($dataInput as $key => &$value) {
                if ($value === '') {
                    $value = null;
                }
            }

            DB::table('role_user')->insert($dataInput);
        }
        fclose($csvFile);
        
        // appointment
        // $appointments = Appointment::factory()->count(30)
        //                                         ->create();
        
        $this->seedFromCSV('app/initial-data/medicine_types.csv', MedicineType::class);
        $this->seedFromCSV('app/initial-data/medicines.csv', Medicine::class);

        // appointment medicine
        // $randomAppointments = $appointments->random(6);

        // $medicines_count = Medicine::all()->count();
        // foreach ($randomAppointments as $appointment) {
        //     $medicineIDs = $this->generateRandomArrayNumberUnique($medicines_count);
        //     foreach ($medicineIDs as $medicineID) {
        //         $medicine = Medicine::find($medicineID);
        //         $randomQuantity = mt_rand(1, 5);

        //         $medicine->update(['stock' => $medicine->stock - $randomQuantity]);

        //         $appointment->medicines()
        //                         ->attach($medicineID, 
        //                                     ['price' => $medicine->price, 
        //                                     'quantity' => $randomQuantity]);
        //     }
        // }

        $this->seedFromCSV('app/initial-data/treatment_types.csv', TreatmentType::class);
        $this->seedFromCSV('app/initial-data/treatments.csv', Treatment::class);
        
        // appointment treatment
        // $treatments_count = Treatment::all()->count();
        // foreach ($appointments as $appointment) {
        //     $treatmentIDs = $this->generateRandomArrayNumberUnique($treatments_count);
        //     foreach ($treatmentIDs as $treatmentID) {
        //         $appointment->treatments()
        //                     ->attach($treatmentID,
        //                                 ['price' => fake()->randomElement([50000, 80000, 150000, 250000]),
        //                                  'note' => fake()->sentence()]);
        //     }
        // }
        
        $this->seedFromCSV('app/initial-data/diseases.csv', Disease::class);
        $this->seedFromCSV('app/initial-data/diagnoses.csv', Diagnosis::class);
        
        // appointment diagnosis
        // $diagnoses_count = Diagnosis::all()->count();
        // foreach ($appointments as $appointment) {
        //     $diagnosisIDs = $this->generateRandomArrayNumberUnique($diagnoses_count);
        //     foreach ($diagnosisIDs as $diagnosisID) {
        //         $appointment->diagnoses()
        //                     ->attach($diagnosisID,
        //                                 ['note' => fake()->randomElement(['', fake()->sentence()])]);
        //     }
        // }
        
        $this->seedFromCSV('app/initial-data/payment_types.csv', PaymentType::class);
        
        // payment
        // $paymentTypesCount = PaymentType::all()->count();
        // foreach ($appointments as $appointment) {
        //     $amount = 0;
        //     foreach ($appointment->medicines as $medicine) {
        //         $amount += $medicine->pivot->price * $medicine->pivot->quantity;
        //     }

        //     foreach ($appointment->treatments as $treatment) {
        //         $amount += $treatment->pivot->price;
        //     }

        //     $payment = Payment::create([
        //         'appointment_id' => $appointment->id,
        //         'amount' => $amount,
        //         'operational_cost' => fake()->randomElement([0, 20000, 50000, 75000]),
        //         'doctor_percentage' => $appointment->doctor->doctor_percentage,
        //         'note' => fake()->randomElement(['', fake()->sentence()]),
        //         'status' => 'Lunas'
        //     ]);

        //     $paymentTypeId = fake()->numberBetween(1, $paymentTypesCount);
        //     $payment->payment_types()->attach($paymentTypeId, ['patient_money' => $amount]);
        // }

        // patient condition
        // foreach ($appointments as $appointment) {
        //     $isPregnant = false;
        //     if ($appointment->patient->gender == 'Perempuan') {
        //         $isPregnant = fake()->boolean();
        //     }
            
        //     PatientCondition::create([
        //         'appointment_id' => $appointment->id,
        //         'is_pregnant' => $isPregnant
        //     ]);
        // }

        // expense
        // Expense::factory()->count(30)
        //                   ->create();

        // Fake tanggal lahir dan phone user
        // $patients = DB::table('patients')->get();
        // foreach ($patients as $patient) {

        //     DB::table('patients')
        //         ->where('id', $patient->id)
        //         ->update([
        //             'date_of_birth' => fake()->date(max:'2023-01-01'),
        //             'phone' => fake()->numerify('08##########')
        //         ]);
        // }
    }
}
