<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Doctor;
use Carbon\CarbonPeriod;
use App\Models\Appointment;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class RecapController extends Controller
{
    public function recapDaily(Request $request) {
        // Inisialisasi query, tanpa eager loading karena akan pake query builder
        $query = Appointment::query()
                ->without(
                    // 'doctor', 
                    'assistant', 
                    'admin', 
                    'patient', 
                    'patient_condition', 
                    'treatments', 
                    'diagnoses', 
                    'medicines', 
                    'status', 
                    // 'payment'
                );

        // Filter tanggal (single date)
        $query->when($request->date, function ($query) use ($request) {
            $date = Carbon::createFromFormat('d-m-Y', $request->date);
            return $query->whereDate('date_time', $date);
        }, function ($query) {
            return $query->whereDate('date_time', now()->today());
        });

        // Pastikan yang sudah dibayar
        $query->has('payment');

        // Dapatkan semua pertemuan
        $appointments = $query->get();
        
        // Group berdasarkan doctor
        $appointmentsGroupByDoctor = $appointments->groupBy('doctor_id');
        // dd($appointmentsGroupByDoctor);
        // dd($appointmentsGroupByDoctor->toArray());

        // Dapatkan semua dokter
        $doctors = Doctor::all();

        // Dapatkan semua tipe pembayaran
        $paymentTypes = PaymentType::all();

        // Hitung total per hari per dokter
        $totalPerDay = [];
        foreach ($doctors as $doctor) {
            $totalPerDay[$doctor->id] = [];
            if (isset($appointmentsGroupByDoctor[$doctor->id])) {
                $totalPerDay[$doctor->id]['doctor_name'] = $doctor->user->name;
                $totalPerDay[$doctor->id]['amount'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return $appointment->payment->amount; });
                $totalPerDay[$doctor->id]['operational_cost'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return $appointment->payment->operational_cost; });
                $totalPerDay[$doctor->id]['lab_cost'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return $appointment->payment->lab_cost; });
                $totalPerDay[$doctor->id]['doctor_cost'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return ($appointment->payment->amount - $appointment->payment->operational_cost - $appointment->payment->lab_cost) * $appointment->payment->doctor_percentage; });
                $totalPerDay[$doctor->id]['clinic_cost'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return $appointment->payment->amount - (($appointment->payment->amount - $appointment->payment->operational_cost - $appointment->payment->lab_cost) * $appointment->payment->doctor_percentage); });
                $totalPerDay[$doctor->id]['zakat'] = $totalPerDay[$doctor->id]['clinic_cost'] * 0.025;
                foreach ($paymentTypes as $paymentType) {
                    $totalPerDay[$doctor->id]['payment_types'][$paymentType->name] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) use($paymentType) { return $appointment->payment->payment_types->where('id', $paymentType->id)->first()->pivot->patient_money ?? 0; });
                }
            } 
            else { // Kalo gak ada pertemuan berarti gak ada penghasilan
                $totalPerDay[$doctor->id]['doctor_name'] = $doctor->user->name;
                $totalPerDay[$doctor->id]['amount'] = 0;
                $totalPerDay[$doctor->id]['operational_cost'] = 0;
                $totalPerDay[$doctor->id]['lab_cost'] = 0;
                $totalPerDay[$doctor->id]['doctor_cost'] = 0;
                $totalPerDay[$doctor->id]['clinic_cost'] = 0;
                $totalPerDay[$doctor->id]['zakat'] = 0;
                foreach ($paymentTypes as $paymentType) {
                    $totalPerDay[$doctor->id]['payment_types'][$paymentType->name] = 0;
                }
            }

            $totalPerDay[$doctor->id]['payment_types'] = collect($totalPerDay[$doctor->id]['payment_types']);
        }

        $totalPerDay = collect($totalPerDay);

        // dd($totalPerDay);

        return view('recap.recap-daily', [
            'totalPerDay' => $totalPerDay
        ]);
    }

    public function recapMonthly(Request $request) {
        // Inisialisasi query, tanpa eager loading karena akan pake query builder
        $query = Appointment::query()
                ->without(
                    // 'doctor', 
                    'assistant', 
                    'admin', 
                    'patient', 
                    'patient_condition', 
                    'treatments', 
                    'diagnoses', 
                    'medicines', 
                    'status', 
                    // 'payment'
                );

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

        // Pastikan yang sudah dibayar
        $query->has('payment');

        // Dapatkan semua pertemuan
        $appointments = $query->select('*', DB::raw('DATE(date_time) as date'))->orderBy('date', 'asc')->get();
        
        // Group berdasarkan date
        $appointmentsGroupByDate = $appointments->groupBy('date');
        // dd($appointmentsGroupByDate);
        // dd($appointmentsGroupByDate->toArray());

        // Buat semua tanggal dari bulan dan tahun yang dipilih
        $period = CarbonPeriod::create(
            Carbon::createFromDate($request->year ?? now()->year, $request->month ?? now()->month)->startOfMonth(), 
            Carbon::createFromDate($request->year ?? now()->year, $request->month ?? now()->month)->endOfMonth()
        );
        
        $dates = [];
        foreach ($period as $date) {
            array_push($dates, $date->format('Y-m-d'));
        }
        // dd($dates);

        // Dapatkan semua dokter
        $doctors = Doctor::all();

        // Dapatkan semua tipe pembayaran
        $paymentTypes = PaymentType::all();

        // Dapatkan pengeluaran
        $expenses = DB::table('expenses')
                    ->when($request->month, function ($query) use ($request) {
                        return $query->whereMonth('date', $request->month);
                    }, function ($query) {
                        return $query->whereMonth('date', now()->month);
                    })
                    ->when($request->year, function ($query) use ($request) {
                        return $query->whereYear('date', $request->year);
                    }, function ($query) {
                        return $query->whereYear('date', now()->year);
                    })
                    ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(amount) as total_amount'))
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();
        $expenses = $expenses->keyBy('date');
        
        // dd($expenses->keyBy('date')->toArray());

        // Hitung totalPerMonth
        $totalPerMonth = [];
        foreach ($dates as $date) {
            $totalPerMonth[$date]['date'] = $date;

            if (isset($appointmentsGroupByDate[$date])) {
                $appointmentsGroupByDoctor = $appointmentsGroupByDate[$date]->groupBy('doctor_id');

                // Hitung total per hari per dokter
                $totalPerDay = [];
                foreach ($doctors as $doctor) {
                    $totalPerDay[$doctor->id] = [];
                    if (isset($appointmentsGroupByDoctor[$doctor->id])) {
                        $totalPerDay[$doctor->id]['doctor_name'] = $doctor->user->name;
                        $totalPerDay[$doctor->id]['amount'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return $appointment->payment->amount; });
                        $totalPerDay[$doctor->id]['operational_cost'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return $appointment->payment->operational_cost; });
                        $totalPerDay[$doctor->id]['lab_cost'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return $appointment->payment->lab_cost; });
                        $totalPerDay[$doctor->id]['doctor_cost'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return ($appointment->payment->amount - $appointment->payment->operational_cost - $appointment->payment->lab_cost) * $appointment->payment->doctor_percentage; });
                        $totalPerDay[$doctor->id]['clinic_cost'] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) { return $appointment->payment->amount - (($appointment->payment->amount - $appointment->payment->operational_cost - $appointment->payment->lab_cost) * $appointment->payment->doctor_percentage); });
                        $totalPerDay[$doctor->id]['zakat'] = $totalPerDay[$doctor->id]['clinic_cost'] * 0.025;
                        foreach ($paymentTypes as $paymentType) {
                            $totalPerDay[$doctor->id]['payment_types'][$paymentType->name] = $appointmentsGroupByDoctor[$doctor->id]->sum(function ($appointment) use($paymentType) { return $appointment->payment->payment_types->where('id', $paymentType->id)->first()->pivot->patient_money ?? 0; });
                        }
                    } 
                    else { // Kalo gak ada pertemuan berarti gak ada penghasilan
                        $totalPerDay[$doctor->id]['doctor_name'] = $doctor->user->name;
                        $totalPerDay[$doctor->id]['amount'] = 0;
                        $totalPerDay[$doctor->id]['operational_cost'] = 0;
                        $totalPerDay[$doctor->id]['lab_cost'] = 0;
                        $totalPerDay[$doctor->id]['doctor_cost'] = 0;
                        $totalPerDay[$doctor->id]['clinic_cost'] = 0;
                        $totalPerDay[$doctor->id]['zakat'] = 0;
                        foreach ($paymentTypes as $paymentType) {
                            $totalPerDay[$doctor->id]['payment_types'][$paymentType->name] = 0;
                        }
                    }

                    $totalPerDay[$doctor->id]['payment_types'] = collect($totalPerDay[$doctor->id]['payment_types']);
                }

                // Udah per doctor
                $totalPerDay = collect($totalPerDay);

                $totalPerMonth[$date]['amount'] = $totalPerDay->sum('amount');
                $totalPerMonth[$date]['operational_cost'] = $totalPerDay->sum('operational_cost');
                $totalPerMonth[$date]['lab_cost'] = $totalPerDay->sum('lab_cost');
                foreach ($doctors as $doctor) {
                    $totalPerMonth[$date]['doctor_cost'][$doctor->user->name] = $totalPerDay[$doctor->id]['doctor_cost'];
                }
                $totalPerMonth[$date]['clinic_cost'] = $totalPerDay->sum('clinic_cost');
                $totalPerMonth[$date]['zakat'] = $totalPerDay->sum('zakat');
                foreach ($paymentTypes as $paymentType) {
                    $totalPerMonth[$date]['payment_types'][$paymentType->name] = $totalPerDay->sum(function ($doctorCost) use ($paymentType) {
                        return $doctorCost['payment_types'][$paymentType->name];
                    });
                }
            } 
            else { // gak ada tanggal berarti gak ada pertemuan
                $totalPerMonth[$date]['amount'] = 0;
                $totalPerMonth[$date]['operational_cost'] = 0;
                $totalPerMonth[$date]['lab_cost'] = 0;
                foreach ($doctors as $doctor) {
                    $totalPerMonth[$date]['doctor_cost'][$doctor->user->name] = 0;
                }
                $totalPerMonth[$date]['clinic_cost'] = 0;
                $totalPerMonth[$date]['zakat'] = 0;
                foreach ($paymentTypes as $paymentType) {
                    $totalPerMonth[$date]['payment_types'][$paymentType->name] = 0;
                }
            }

            $totalPerMonth[$date]['doctor_cost'] = collect($totalPerMonth[$date]['doctor_cost']);
            $totalPerMonth[$date]['payment_types'] = collect($totalPerMonth[$date]['payment_types']);

            if (isset($expenses[$date])) {
                $totalPerMonth[$date]['expenses'] = floatval($expenses[$date]->total_amount);
            } else {
                $totalPerMonth[$date]['expenses'] = 0;
            }

            $totalPerMonth[$date]['netto'] = $totalPerMonth[$date]['clinic_cost'] - $totalPerMonth[$date]['zakat'] - $totalPerMonth[$date]['expenses'];

        }

        $totalPerMonth = collect($totalPerMonth);

        // dd($totalPerMonth);

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
            $title = 'Rekap_bulanan_' . ($request->year ?? now()->year) . '-' . ($request->month ? sprintf("%02d", $request->month) : now()->format('m'));
            $pdf = Pdf::loadView('recap.print-recap-monthly', [
                'title' => $title,
                'totalPerMonth' => $totalPerMonth,
                'months' => $months,
                'years' => $years
            ])->setPaper('a4', 'landscape');
    
            return $pdf->stream($title);
        }

        return view('recap.recap-monthly', [
            'totalPerMonth' => $totalPerMonth,
            'months' => $months,
            'years' => $years
        ]);
    }
}
