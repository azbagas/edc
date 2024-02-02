<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Disease;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\TreatmentType;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function communityHealthCenterDaily(Request $request) {
        $query = Appointment::query();

        $startDate = now()->today();
        $endDate = now()->today();

        // Filter waktu
        $query->when($request->start_date, function ($query) use ($request, &$startDate, &$endDate) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $endDate = Carbon::createFromFormat('d-m-Y', $request->end_date);
            return $query->whereBetween('date_time', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }, function ($query) {
            return $query->whereDate('date_time', now()->today());
        });

        $appointments = $query->get();

        // I. PatientTypes
        $patientTypes = [
            ['id' => 0, 'name' => 'Kunjungan rawat jalan ibu hamil'],
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

                    if ($appointment->patient_condition->is_pregnant == 1) {
                        $patientTypesCount[0]['countFemaleNew']++;
                    } else if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
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

                    if ($appointment->patient_condition->is_pregnant == 1) {
                        $patientTypesCount[0]['countFemaleOld']++;
                    } else if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
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

        if ($request->download == 'pdf') {
            if ($startDate->format('d-m-Y') == $endDate->format('d-m-Y')) {
                $title = 'Laporan_untuk_puskesmas_' . ($startDate->format('d-m-Y'));
            } else {
                $title = 'Laporan_untuk_puskesmas_' . ($startDate->format('d-m-Y')) . '_' . ($endDate->format('d-m-Y'));
            }

            $pdf = Pdf::loadView('reports.print-community-health-center-daily', [
                'title' => $title,
                'patientTypesCount' => $patientTypesCount,
                'treatmentTypesCount' => $treatmentTypesCount,
                'diseasesCount' => $diseasesCount,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'doctor' => Doctor::findOrFail(1)
            ]);
    
            return $pdf->stream($title);
        }

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
            ['id' => 0, 'name' => 'Kunjungan rawat jalan ibu hamil'],
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
                    
                    if ($appointment->patient_condition->is_pregnant == 1) {
                        $patientTypesCount[0]['countFemaleNew']++;
                    } else if (Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
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

                    if ($appointment->patient_condition->is_pregnant == 1) {
                        $patientTypesCount[0]['countFemaleOld']++;
                    } else if(Carbon::parse($appointment->patient->date_of_birth)->diffInYears(Carbon::parse($appointment->date_time)) <= 6) {
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

        if ($request->download == 'pdf') {
            $title = 'Laporan_untuk_puskesmas_' . ($request->year ?? now()->year) . '-' . ($request->month ?? now()->format('m'));
            $pdf = Pdf::loadView('reports.print-community-health-center-monthly', [
                'title' => $title,
                'patientTypesCount' => $patientTypesCount,
                'treatmentTypesCount' => $treatmentTypesCount,
                'diseasesCount' => $diseasesCount,
                'months' => $months,
                'years' => $years,
                'doctor' => Doctor::findOrFail(1)
            ]);
    
            return $pdf->stream($title);
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
