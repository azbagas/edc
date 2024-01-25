<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Disease;
use App\Models\TreatmentType;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function communityHealthCenterDaily(Request $request) {
        $query = Appointment::query();

        // Filter waktu
        $query->when($request->start_date, function ($query) use ($request) {
            $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date);
            return $query->whereBetween('date_time', [$start_date->startOfDay(), $end_date->endOfDay()]);
        }, function ($query) {
            return $query->whereDate('date_time', now()->today());
        });

        $appointments = $query->get();

        // I. PatientTypes
        $patientTypes = [
            ['id' => 1, 'name' => 'Kunjungan rawat jalan gigi anak ( 1 - 6 th)'],
            ['id' => 2, 'name' => 'Kunjungan rawat jalan gigi golongan penderita lain']
        ];
        $patientTypesCount = [];

        foreach ($patientTypes as $patientType) {
            $patientTypesCount[$patientType['id']] = [
                'patient_type' => $patientType['name'],
                'countMaleNew' => 0,
                'countMaleOld' => 0,
                'countFemaleNew' => 0,
                'countFemaleOld' => 0
            ];
        }

        // II. TreatmentTypes
        $treatmentTypes = TreatmentType::orderBy('name', 'asc')->get();
        $treatmentTypesCount = [];

        foreach ($treatmentTypes as $treatmentType) {
            $treatmentTypesCount[$treatmentType->id] = [
                'treatment_type' => $treatmentType,
                'countMaleNew' => 0,
                'countMaleOld' => 0,
                'countFemaleNew' => 0,
                'countFemaleOld' => 0
            ];
        }

        // III. Diseases
        $diseases = Disease::orderBy('disease_code', 'asc')->get();
        $diseasesCount = [];

        foreach ($diseases as $disease) {
            $diseasesCount[$disease->id] = [
                'disease' => $disease,
                'countMaleNew' => 0,
                'countMaleOld' => 0,
                'countFemaleNew' => 0,
                'countFemaleOld' => 0
            ];
        }

        foreach ($appointments as $appointment) {
            if ($appointment->patient->created_at->isSameDay(Carbon::parse($appointment->date_time))) {
                if ($appointment->patient->gender == 'Laki-laki') {
                    foreach ($appointment->treatments as $treatment) {
                        $treatmentTypesCount[$treatment->treatment_type->id]['countMaleNew']++;
                    }
                    foreach ($appointment->diagnoses as $diagnosis) {
                        $diseasesCount[$diagnosis->disease->id]['countMaleNew']++;
                    }

                    if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
                        // Kurang dari 6 tahun
                        $patientTypesCount[1]['countMaleNew']++;
                    } else {
                        $patientTypesCount[2]['countMaleNew']++;
                    }
                } else {
                    foreach ($appointment->treatments as $treatment) {
                        $treatmentTypesCount[$treatment->treatment_type->id]['countFemaleNew']++;
                    }
                    foreach ($appointment->diagnoses as $diagnosis) {
                        $diseasesCount[$diagnosis->disease->id]['countFemaleNew']++;
                    }

                    if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
                        // Kurang dari 6 tahun
                        $patientTypesCount[1]['countFemaleNew']++;
                    } else {
                        $patientTypesCount[2]['countFemaleNew']++;
                    }
                }
            } else {
                if ($appointment->patient->gender == 'Laki-laki') {
                    foreach ($appointment->treatments as $treatment) {
                        $treatmentTypesCount[$treatment->treatment_type->id]['countMaleOld']++;
                    }
                    foreach ($appointment->diagnoses as $diagnosis) {
                        $diseasesCount[$diagnosis->disease->id]['countMaleOld']++;
                    }

                    if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
                        // Kurang dari 6 tahun
                        $patientTypesCount[1]['countMaleOld']++;
                    } else {
                        $patientTypesCount[2]['countMaleOld']++;
                    }
                } else {
                    foreach ($appointment->treatments as $treatment) {
                        $treatmentTypesCount[$treatment->treatment_type->id]['countFemaleOld']++;
                    }
                    foreach ($appointment->diagnoses as $diagnosis) {
                        $diseasesCount[$diagnosis->disease->id]['countFemaleOld']++;
                    }

                    if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
                        // Kurang dari 6 tahun
                        $patientTypesCount[1]['countFemaleOld']++;
                    } else {
                        $patientTypesCount[2]['countFemaleOld']++;
                    }
                }
            }
        }
        
        $patientTypesCount = array_values($patientTypesCount);
        $treatmentTypesCount = array_values($treatmentTypesCount);
        $diseasesCount = array_values($diseasesCount);

        return view('reports.community-health-center-daily', [
            'patientTypesCount' => $patientTypesCount,
            'treatmentTypesCount' => $treatmentTypesCount,
            'diseasesCount' => $diseasesCount
        ]);
    }

    public function communityHealthCenterMonthly(Request $request) {
        $query = Appointment::query();

        // Filter waktu berdasarkan month dan year dari request
        $query->when($request->month, function ($query) use ($request) {
            return $query->whereMonth('date_time', $request->month);
        }, function ($query) {
            return $query->whereMonth('date_time', now()->month);
        });

        $query->when($request->year, function ($query) use ($request) {
            return $query->whereYear('date_time', $request->year);
        }, function ($query) {
            return $query->whereYear('date_time', now()->year);
        });

        $appointments = $query->get();

        // I. PatientTypes
        $patientTypes = [
            ['id' => 1, 'name' => 'Kunjungan rawat jalan gigi anak ( 1 - 6 th)'],
            ['id' => 2, 'name' => 'Kunjungan rawat jalan gigi golongan penderita lain']
        ];
        $patientTypesCount = [];

        foreach ($patientTypes as $patientType) {
            $patientTypesCount[$patientType['id']] = [
                'patient_type' => $patientType['name'],
                'countMaleNew' => 0,
                'countMaleOld' => 0,
                'countFemaleNew' => 0,
                'countFemaleOld' => 0
            ];
        }

        // II. TreatmentTypes
        $treatmentTypes = TreatmentType::orderBy('name', 'asc')->get();
        $treatmentTypesCount = [];

        foreach ($treatmentTypes as $treatmentType) {
            $treatmentTypesCount[$treatmentType->id] = [
                'treatment_type' => $treatmentType,
                'countMaleNew' => 0,
                'countMaleOld' => 0,
                'countFemaleNew' => 0,
                'countFemaleOld' => 0
            ];
        }

        // III. Diseases
        $diseases = Disease::orderBy('disease_code', 'asc')->get();
        $diseasesCount = [];

        foreach ($diseases as $disease) {
            $diseasesCount[$disease->id] = [
                'disease' => $disease,
                'countMaleNew' => 0,
                'countMaleOld' => 0,
                'countFemaleNew' => 0,
                'countFemaleOld' => 0
            ];
        }

        foreach ($appointments as $appointment) {
            if ($appointment->patient->created_at->isSameDay(Carbon::parse($appointment->date_time))) {
                if ($appointment->patient->gender == 'Laki-laki') {
                    foreach ($appointment->treatments as $treatment) {
                        $treatmentTypesCount[$treatment->treatment_type->id]['countMaleNew']++;
                    }
                    foreach ($appointment->diagnoses as $diagnosis) {
                        $diseasesCount[$diagnosis->disease->id]['countMaleNew']++;
                    }

                    if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
                        // Kurang dari 6 tahun
                        $patientTypesCount[1]['countMaleNew']++;
                    } else {
                        $patientTypesCount[2]['countMaleNew']++;
                    }
                } else {
                    foreach ($appointment->treatments as $treatment) {
                        $treatmentTypesCount[$treatment->treatment_type->id]['countFemaleNew']++;
                    }
                    foreach ($appointment->diagnoses as $diagnosis) {
                        $diseasesCount[$diagnosis->disease->id]['countFemaleNew']++;
                    }

                    if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
                        // Kurang dari 6 tahun
                        $patientTypesCount[1]['countFemaleNew']++;
                    } else {
                        $patientTypesCount[2]['countFemaleNew']++;
                    }
                }
            } else {
                if ($appointment->patient->gender == 'Laki-laki') {
                    foreach ($appointment->treatments as $treatment) {
                        $treatmentTypesCount[$treatment->treatment_type->id]['countMaleOld']++;
                    }
                    foreach ($appointment->diagnoses as $diagnosis) {
                        $diseasesCount[$diagnosis->disease->id]['countMaleOld']++;
                    }

                    if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
                        // Kurang dari 6 tahun
                        $patientTypesCount[1]['countMaleOld']++;
                    } else {
                        $patientTypesCount[2]['countMaleOld']++;
                    }
                } else {
                    foreach ($appointment->treatments as $treatment) {
                        $treatmentTypesCount[$treatment->treatment_type->id]['countFemaleOld']++;
                    }
                    foreach ($appointment->diagnoses as $diagnosis) {
                        $diseasesCount[$diagnosis->disease->id]['countFemaleOld']++;
                    }

                    if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
                        // Kurang dari 6 tahun
                        $patientTypesCount[1]['countFemaleOld']++;
                    } else {
                        $patientTypesCount[2]['countFemaleOld']++;
                    }
                }
            }
        }
        
        $patientTypesCount = array_values($patientTypesCount);
        $treatmentTypesCount = array_values($treatmentTypesCount);
        $diseasesCount = array_values($diseasesCount);

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',   
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Buat array associative untuk tahun dari 2020 sampai tahun sekarang ditambah 5 tahun ke depan
        $years = [];
        for ($i = 2020; $i <= now()->addYears(5)->year; $i++) {
            $years[$i] = $i;
        }

        return view('reports.community-health-center-monthly', [
            'patientTypesCount' => $patientTypesCount,
            'treatmentTypesCount' => $treatmentTypesCount,
            'diseasesCount' => $diseasesCount,
            'months' => $months,
            'years' => $years
        ]);
    }
}
