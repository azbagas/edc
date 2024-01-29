<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Payment;
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
                 ->without('doctor', 'assistant', 'admin', 'patient', 'treatments', 'diagnoses', 'medicines', 'status', 'payment');

        // Filter tanggal (single date)
        $query->when($request->date, function ($query) use ($request) {
            $date = Carbon::createFromFormat('d-m-Y', $request->date);
            return $query->whereDate('date_time', $date);
        }, function ($query) {
            return $query->whereDate('date_time', now()->today());
        });

        // Pastikan yang sudah dibayar
        $query->has('payment');
        
        // Query untuk mendapatkan total per hari
        $results = $query->join('doctors', 'doctors.id', '=', 'appointments.doctor_id')
                        ->join('users', 'users.id', '=', 'doctors.user_id')
                        ->join('payments', 'payments.appointment_id', '=', 'appointments.id')
                        ->join('payment_types', 'payment_types.id', '=', 'payments.payment_type_id')
                        ->select('doctor_id',
                                'users.name as doctor_name',
                                DB::raw('SUM(amount) as total_amount'),
                                DB::raw('SUM(operational_cost) as total_operational_cost'),
                                DB::raw('SUM(lab_cost) as total_lab_cost'),
                                DB::raw('SUM((amount - operational_cost - lab_cost) * payments.doctor_percentage) as total_doctor_cost'),
                                DB::raw('SUM(amount - ((amount - operational_cost - lab_cost) * payments.doctor_percentage)) as total_clinic_total'),
                                'payment_type_id',
                                'payment_types.name as payment_type_name'
                        )
                        ->groupBy('payment_type_id')
                        ->groupBy('doctor_id')
                        ->get();

        // Get semua dokter, users.name dan doctor_id
        $doctors = Doctor::all();

        // Get semua payment types
        $paymentTypes = PaymentType::all();

        // Buat array kosong untuk menampung hasil
        $resultsArray = [];

        // Looping semua dokter
        foreach ($doctors as $doctor) {
            // Looping semua payment types
            foreach ($paymentTypes as $paymentType) {
                // Buat array kosong untuk menampung hasil
                $result = [];

                // Looping semua hasil query
                foreach ($results as $res) {
                    // Jika hasil query memiliki doctor_id dan payment_type_name yang sama dengan dokter dan payment type yang sedang di-looping
                    if ($res->doctor_id == $doctor->id && $res->payment_type_id == $paymentType->id) {
                        // Tambahkan hasil query ke array kosong
                        $result = $res->toArray();
                    }
                }

                // Jika ada di result
                if (!empty($result)) {
                    // Tambahkan hasil query ke array kosong
                    array_push($resultsArray, $result);
                } else {
                    // Buat array kosong untuk menampung hasil
                    $result = [];

                    // Tambahkan hasil query ke array kosong
                    $result['doctor_id'] = $doctor->id;
                    $result['doctor_name'] = $doctor->user->name;
                    $result['total_amount'] = 0;
                    $result['total_operational_cost'] = 0;
                    $result['total_lab_cost'] = 0;
                    $result['total_doctor_cost'] = 0;
                    $result['total_clinic_total'] = 0;
                    $result['payment_type_id'] = $paymentType->id;
                    $result['payment_type_name'] = $paymentType->name;

                    // Tambahkan hasil query ke array kosong
                    array_push($resultsArray, $result);
                }
            }
        }

        // Buat menjadi array 2 dimensi, array pertama adalah dokter, array kedua adalah payment type. Key dari array pertama adalah doctor_id, key dari array kedua adalah payment_type_name
        $resultsArray = collect($resultsArray)->groupBy('doctor_id');
        
        // Buat key dari array di dalam array menjadi payment_type_id
        foreach ($resultsArray as $key => $value) {
            $resultsArray[$key] = collect($value)->keyBy('payment_type_id');
        }

        // dd($resultsArray->toArray());

        // Dari resultArray tersebut, buat menjadi array 1 dimensi yang berisi 'doctor_name', 'sum_total_amount', 'sum_total_doctor_cost', 'sum_total_clinic_total', 'cash_cost', 'debit_cost'
        // Ini di-sum untuk menyatukan total dari semua payment type dan akan ditampilkan di view
        foreach ($resultsArray as $doctorId => $paymentTypes) {
            $paymentTypeCosts = [];

            foreach ($paymentTypes as $paymentTypeId => $payment) {
                $paymentTypeCosts[$payment['payment_type_name']] = floatval($payment['total_amount']);
            }

            $resultsArray[$doctorId] = collect([
                'doctor_name' => $paymentTypes->first()['doctor_name'],
                'sum_total_amount' => floatval($paymentTypes->sum('total_amount')),
                'sum_total_operational_cost' => floatval($paymentTypes->sum('total_operational_cost')),
                'sum_total_lab_cost' => floatval($paymentTypes->sum('total_lab_cost')),
                'sum_total_doctor_cost' => floatval($paymentTypes->sum('total_doctor_cost')),
                'sum_total_clinic_total' => floatval($paymentTypes->sum('total_clinic_total')),
                'payment_types' => collect($paymentTypeCosts)
            ]);
        }
        // dd($resultsArray->toArray());

        return view('recap.recap-daily', [
            'doctorCosts' => $resultsArray
        ]);
    }

    public function recapMonthly(Request $request) {
        // Inisialisasi query, tanpa eager loading karena akan pake query builder
        $query = Appointment::query()
                 ->without('doctor', 'assistant', 'admin', 'patient', 'treatments', 'diagnoses', 'medicines', 'status', 'payment');

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

        // Dapatkan hasil query seperti recapDaily, tapi dengan group by date kolom date_time
        $results = $query->join('doctors', 'doctors.id', '=', 'appointments.doctor_id')
                        ->join('users', 'users.id', '=', 'doctors.user_id')
                        ->join('payments', 'payments.appointment_id', '=', 'appointments.id')
                        ->join('payment_types', 'payment_types.id', '=', 'payments.payment_type_id')
                        ->select('doctor_id',
                                'users.name as doctor_name', 
                                DB::raw('SUM(amount) as total_amount'),
                                DB::raw('SUM(operational_cost) as total_operational_cost'),
                                DB::raw('SUM(lab_cost) as total_lab_cost'),
                                DB::raw('SUM((amount - operational_cost - lab_cost) * payments.doctor_percentage) as total_doctor_cost'),
                                DB::raw('SUM(amount - ((amount - operational_cost - lab_cost) * payments.doctor_percentage)) as total_clinic_total'),
                                'payment_type_id',
                                'payment_types.name as payment_type_name',
                                DB::raw('DATE(date_time) as date')
                        )
                        ->groupBy('payment_type_id')
                        ->groupBy('doctor_id')
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();

        // Buat semua tanggal dari bulan dan tahun yang dipilih
        $period = CarbonPeriod::create(
            Carbon::createFromDate($request->year ?? now()->year, $request->month ?? now()->month)->startOfMonth(), 
            Carbon::createFromDate($request->year ?? now()->year, $request->month ?? now()->month)->endOfMonth()
        );
        
        $dates = [];
        foreach ($period as $date) {
            array_push($dates, $date->format('Y-m-d'));
        }

        // Get semua dokter, users.name dan doctor_id
        $doctors = Doctor::all();

        // Get semua payment types
        $paymentTypes = PaymentType::all();

        // Buat collection kosong untuk menampung hasil
        $resultsArray = [];

        // Looping semua dates
        foreach ($dates as $date) {
            // Looping semua dokter
            foreach ($doctors as $doctor) {
                // Looping semua payment types
                foreach ($paymentTypes as $paymentType) {
                    // Buat array kosong untuk menampung hasil
                    $result = [];

                    // Looping semua hasil query
                    foreach ($results as $res) {
                        // Jika hasil query memiliki doctor_id dan payment_type_name yang sama dengan dokter dan payment type yang sedang di-looping
                        if ($res->doctor_id == $doctor->id && $res->payment_type_id == $paymentType->id && $res->date == $date) {
                            // Tambahkan hasil query ke array kosong
                            $result = $res->toArray();
                        }
                    }

                    // Jika ada di result
                    if (!empty($result)) {
                        // Tambahkan hasil query ke array kosong
                        array_push($resultsArray, collect($result));
                    } else {
                        // Buat array kosong untuk menampung hasil
                        $result = [];

                        // Tambahkan hasil query ke array kosong
                        $result['doctor_id'] = $doctor->id;
                        $result['doctor_name'] = $doctor->user->name;
                        $result['total_amount'] = 0;
                        $result['total_operational_cost'] = 0;
                        $result['total_lab_cost'] = 0;
                        $result['total_doctor_cost'] = 0;
                        $result['total_clinic_total'] = 0;
                        $result['payment_type_id'] = $paymentType->id;
                        $result['payment_type_name'] = $paymentType->name;
                        $result['date'] = $date;

                        // Tambahkan hasil query ke array kosong
                        array_push($resultsArray, collect($result));
                    }
                }
            }
        }
        
        // Buat menjadi collection
        $resultsArray = collect($resultsArray);
        
        // Buat collection 3 dimensi, collection pertama adalah tanggal, collection kedua adalah dokter, collection ketiga adalah payment type. Key dari collection pertama adalah date, key dari collection kedua adalah doctor_id, key dari collection ketiga adalah payment_type_id
        $resultsArray = $resultsArray->groupBy('date');
        foreach ($resultsArray as $key => $value) {
            $resultsArray[$key] = $value->groupBy('doctor_id');
            foreach ($resultsArray[$key] as $key2 => $value2) {
                $resultsArray[$key][$key2] = $value2->keyBy('payment_type_id');
            }
        }

        // dd($resultsArray->toArray());

        // Dari resultArray tersebut, buat menjadi collection 1 dimensi yang berisi date, sum_total_amount, sum_total_operational_cost, sum_total_doctor_cost_1, sum_total_doctor_cost_2, ..., sum_total_clinic_total, sum_cash_cost, sum_debit_cost
        // Ini di-sum untuk menyatukan total dari semua payment type dan akan ditampilkan di view
        foreach ($resultsArray as $date => $doctors) {
            foreach ($doctors as $doctorId => $paymentTypes) {
                $paymentTypeCosts = [];

                foreach ($paymentTypes as $paymentTypeId => $payment) {
                    $paymentTypeCosts[$payment['payment_type_name']] = floatval($payment['total_amount']);
                }

                // Ngejumlahin keseluruhan payment type di tiap dokter
                $doctors[$doctorId] = collect([
                    'date' => $date,
                    'doctor_name' => $paymentTypes->first()['doctor_name'],
                    'doctor_id' => $paymentTypes->first()['doctor_id'],
                    'sum_total_amount' => floatval($paymentTypes->sum('total_amount')),
                    'sum_total_operational_cost' => floatval($paymentTypes->sum('total_operational_cost')),
                    'sum_total_lab_cost' => floatval($paymentTypes->sum('total_lab_cost')),
                    'sum_total_doctor_cost' => floatval($paymentTypes->sum('total_doctor_cost')),
                    'sum_total_clinic_total' => floatval($paymentTypes->sum('total_clinic_total')),
                    'zakat' => floatval($paymentTypes->sum('total_clinic_total')) * 0.025,
                    'payment_types' => collect($paymentTypeCosts)
                ]);
            }
        }

        $finalResults = [];

        // dd($resultsArray->toArray());

        // Dapatkan pengeluaran dari Expense filter berdasarkan month dan year dari request, lalu sum berdasarkan amount dan group by date
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
        
        // dd($expenses->toArray());
        

        foreach ($resultsArray as $date => $doctors) {
            // Perulangan tanggal

            $doctorCosts = [];
            $paymentTypeCosts = [];

            foreach ($doctors as $doctorId => $payment) {
                // Perulangan dokter

                $doctorCosts[$payment['doctor_name']] = $payment['sum_total_doctor_cost'];

                foreach ($payment['payment_types'] as $paymentTypeName => $paymentTypeCost) {
                    if (isset($paymentTypeCosts[$paymentTypeName])) {
                        $paymentTypeCosts[$paymentTypeName] += $paymentTypeCost;
                    } else {
                        $paymentTypeCosts[$paymentTypeName] = $paymentTypeCost;
                    }
                }
            }

            $temp = [];

            // dapatkan pengeluaran dari Expense berdasarkan tanggal
            $expense = $expenses->where('date', $date)->first();

            // Ngejumlahin keseluruhan dokter
            $temp = [
                'date' => $date,
                'sum_total_amount' => floatval($doctors->sum('sum_total_amount')),
                'sum_total_operational_cost' => floatval($doctors->sum('sum_total_operational_cost')),
                'sum_total_lab_cost' => floatval($doctors->sum('sum_total_lab_cost')),
                'doctors' => $doctorCosts,
                'sum_total_clinic_total' => floatval($doctors->sum('sum_total_clinic_total')),
                'zakat' => floatval($doctors->sum('zakat')),
                'expenses' => $expense ? floatval($expense->total_amount) : 0,
                'payment_types' => collect($paymentTypeCosts)
            ];

            $temp['netto'] = $temp['sum_total_clinic_total'] - $temp['zakat'] - $temp['expenses'];

            $finalResults[$date] = collect($temp);
        }

        $finalResults = collect($finalResults);

        // dd($finalResults->toArray());

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

        // dd($finalResults->toArray());
        if ($request->download == 'pdf') {
            $title = 'Rekap_bulanan_' . ($request->year ?? now()->year) . '-' . ($request->month ?? now()->format('m'));
            $pdf = Pdf::loadView('recap.print-recap-monthly', [
                'title' => $title,
                'finalResults' => $finalResults,
                'months' => $months,
                'years' => $years
            ])->setPaper('a4', 'landscape');
    
            return $pdf->stream($title);
        }

        return view('recap.recap-monthly', [
            'finalResults' => $finalResults,
            'months' => $months,
            'years' => $years
        ]);
    }
}
