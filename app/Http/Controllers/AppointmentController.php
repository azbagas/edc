<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Status;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Medicine;
use App\Models\Assistant;
use App\Models\Appointment;
use App\Models\PaymentType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Appointment::query();

        $query->when($request->doctor, function ($query) use ($request) {
            return $query->where('doctor_id', $request->doctor);
        });

        $query->when($request->status, function ($query) use ($request) {
            return $query->where('status_id', $request->status);
        });

        $query->when($request->start_date, function ($query) use ($request) {
            $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date);
            return $query->whereBetween('date_time', [$start_date->startOfDay(), $end_date->endOfDay()]);
        }, function ($query) {
            return $query->whereDate('date_time', now()->today());
        });

        $per_page = $request->per_page ?? 10;

        session(['appointments_url' => request()->fullUrl()]);

        return view('appointments.index', [
            'appointments' => $query->orderBy('date_time', 'desc')->paginate($per_page)->appends($request->all()),
            'doctors' => Doctor::all(),
            'statuses' => Status::all(),
            'per_page_options' => [10, 25, 50]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patient = Patient::findOrFail($request->patient);
        
        return view('appointments.create', [
            'patient' => $patient,
            'doctors' => Doctor::active()->get(),
            'assistants' => Assistant::active()->get()
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $validatedData = $request->validate([
            'date_time' => 'required|date_format:Y-m-d H:i',
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'assistant_id' => 'required',
            'complaint' => 'required',
        ]);

        $validatedData['admin_id'] = Auth::user()->admin->id;
        $validatedData['status_id'] = 1; // menunggu

        // set session untuk dokter dan asisten hari ini
        if (session('todayDoctor') != $validatedData['doctor_id']) {
            session(['todayDoctor' => $validatedData['doctor_id']]);
        }
        if (session('todayAssistant') != $validatedData['assistant_id']) {
            session(['todayAssistant' => $validatedData['assistant_id']]);
        }

        Appointment::create($validatedData);

        return redirect('/appointments')->with('success', 'Pertemuan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment, Request $request)
    {
        if (!$appointment->payment) {
            return redirect()->back()->with('info', 'Belum diperiksa');
        }

        $subTotalTreatments = 0;
        if (!$appointment->treatments->isEmpty()) {
            foreach ($appointment->treatments as $treatment) {
                $subTotalTreatments += $treatment->pivot->price;
            }
        }

        $subTotalMedicines = 0;
        if (!$appointment->medicines->isEmpty()) {
            foreach ($appointment->medicines as $medicine) {
                $tempTotal = $medicine->pivot->quantity * $medicine->pivot->price;
                $subTotalMedicines += $tempTotal;
            }
        }

        if ($request->download == 'pdf') {
            $title = $appointment->id . '_' . $appointment->patient_id . '_' . str_replace(' ', '_', trim($appointment->patient->name));
            $pdf = Pdf::loadView('appointments.print-detail', [
                'title' => $title,
                'appointment' => $appointment,
                'subTotalTreatments' => $subTotalTreatments,
                'subTotalMedicines' => $subTotalMedicines
            ]);
    
            return $pdf->stream($title);
        }

        return view('appointments.show', [
            'appointment' => $appointment,
            'subTotalTreatments' => $subTotalTreatments,
            'subTotalMedicines' => $subTotalMedicines
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        return view('appointments.edit', [
            'appointment' => $appointment,
            'doctors' => Doctor::active()->get(),
            'assistants' => Assistant::active()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $validatedData = $request->validate([
            'date_time' => 'required|date_format:Y-m-d H:i',
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'assistant_id' => 'required',
            'complaint' => 'required',
        ]);

        $appointment->update($validatedData);
        
        // Kalo sudah melakukan payment maka ubah doctor_percentage karena takut dokter yang diubah
        if ($appointment->payment) {
            $payment = $appointment->payment;
            $doctor = Doctor::find($validatedData['doctor_id']);

            $payment->update([
                'doctor_percentage' => $doctor->doctor_percentage
            ]);
        }

        return redirect($request->fromUrl)->with('success', 'Pertemuan berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        try {
            DB::transaction(function () use($appointment) {
                // Kembalikan stok obat
                if ($appointment->medicines) {
                    foreach ($appointment->medicines as $medicine) {
                        $medicine->update([
                            'stock' => $medicine->stock + $medicine->pivot->quantity
                        ]);
                    }
                }

                // Hapus appointment
                Appointment::destroy($appointment->id);
            });
            
            return redirect(session('appointments_url', '/appointments'))->with('success', 'Pertemuan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('appointments_url', '/appointments'))->with('error', 'Gagal menghapus pertemuan!' . $e->getMessage());
        }
    }

    // ----- Examination

    public function examination(Appointment $appointment)
    {
        if ($appointment->status_id == 1) {
            // ubah ke sedang diperiksa
            $appointment->update([
                'status_id' => 2
            ]);
        }

        return view('appointments.examination', [
            'appointment' => $appointment,
            'appointmentHistories' => Appointment::where('patient_id', $appointment->patient_id)->where('id', '<>', $appointment->id)->orderByDesc('date_time')->get()
        ]);
    }

    public function examination_update(Request $request, Appointment $appointment)
    {
        // Fix format harga
        if ($request->treatment_price) {
            $treatmentPrices = $request->treatment_price;

            foreach ($treatmentPrices as $key => $value) {
                $treatmentPrices[$key] = change_currency_format_to_decimal($value);
            }

            $request->merge(['treatment_price' => $treatmentPrices]);
        }

        try {
            DB::transaction(function () use($request, $appointment) {
                // Masukkan diagnosis
                if ($request->diagnosis) {
                    $requestDiagnoses = $request->diagnosis;
                    $requestDiagnosisNotes = $request->diagnosis_note;
                    $diagnosisIn = [];
                    
                    foreach ($requestDiagnoses as $i => $diagnosisId) {
                        $diagnosisIn[$diagnosisId] = ['note' => $requestDiagnosisNotes[$i]];
                    }

                    $appointment->diagnoses()->sync($diagnosisIn);
                } else {
                    if ($appointment->diagnoses) {
                        $appointment->diagnoses()->detach();
                    }
                }

                // Masukkan treatment
                if ($request->treatment) {
                    $requestTreatments = $request->treatment;
                    $requestTreatmentNotes = $request->treatment_note;
                    $requestTreatmentPrices = $request->treatment_price;
                    $treatmentIn = [];

                    foreach ($requestTreatments as $i => $treatmentId) {
                        $treatmentIn[$treatmentId] = [
                            'note' => $requestTreatmentNotes[$i], 
                            'price' => $requestTreatmentPrices[$i]
                        ];
                    }

                    $appointment->treatments()->sync($treatmentIn);
                } else {
                    if ($appointment->treatments) {
                        $appointment->treatments()->detach();
                    }
                }

                // Masukkan medicine
                // Kalo sudah ada medicine, maka balikin dulu semua stoknya
                if ($appointment->medicines) {
                    foreach ($appointment->medicines as $medicine) {
                        $medicine->update([
                            'stock' => $medicine->stock + $medicine->pivot->quantity
                        ]);
                    }
                }

                $dbMedicines = Medicine::all();
                if ($request->medicine) {
                    $requestMedicines = $request->medicine;
                    $requestMedicineQty = $request->medicine_quantity;
                    $medicineIn = [];

                    foreach ($requestMedicines as $i => $medicineId) {
                        $medicine = $dbMedicines->find($medicineId);

                        // Kurangi stok obat
                        $medicine->update([
                            'stock' => $medicine->stock - $requestMedicineQty[$i]
                        ]);

                        $medicineIn[$medicineId] = [
                            'quantity' => $requestMedicineQty[$i], 
                            'price' => $medicine->price
                        ];
                    }

                    $appointment->medicines()->sync($medicineIn);
                } else {
                    if ($appointment->medicines) {
                        $appointment->medicines()->detach();
                    }
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect('/appointments/' . $appointment->id . '/payment');
    }

    public function payment(Appointment $appointment)
    {
        $subTotalTreatments = 0;
        if (!$appointment->treatments->isEmpty()) {
            foreach ($appointment->treatments as $treatment) {
                $subTotalTreatments += $treatment->pivot->price;
            }
        }

        $subTotalMedicines = 0;
        if (!$appointment->medicines->isEmpty()) {
            foreach ($appointment->medicines as $medicine) {
                $tempTotal = $medicine->pivot->quantity * $medicine->pivot->price;
                $subTotalMedicines += $tempTotal;
            }
        }

        $grandTotal = $subTotalTreatments + $subTotalMedicines;

        return view('appointments.payment', [
            'appointment' => $appointment,
            'subTotalTreatments' => $subTotalTreatments,
            'subTotalMedicines' => $subTotalMedicines,
            'grandTotal' => $grandTotal,
            'paymentTypes' => PaymentType::all()
        ]);
    }

    public function payment_update(Appointment $appointment, Request $request)
    {
        // Change format operational cost
        if ($request->operational_cost) {
            $request->merge(['operational_cost' => change_currency_format_to_decimal($request->operational_cost)]);
        }

        if ($request->lab_cost) {
            $request->merge(['lab_cost' => change_currency_format_to_decimal($request->lab_cost)]);
        }

        if ($request->patient_money) {
            $request->merge(['patient_money' => change_currency_format_to_decimal($request->patient_money)]);
        }

        $validatedData = $request->validate([
            'payment_type_id' => 'required',
            'operational_cost' => 'required',
            'lab_cost' => 'required',
            'patient_money' => 'required',
            'note' => 'nullable',
            'date_time' => 'nullable|date_format:Y-m-d H:i',
        ]);

        $subTotalTreatments = 0;
        if (!$appointment->treatments->isEmpty()) {
            foreach ($appointment->treatments as $treatment) {
                $subTotalTreatments += $treatment->pivot->price;
            }
        }

        $subTotalMedicines = 0;
        if (!$appointment->medicines->isEmpty()) {
            foreach ($appointment->medicines as $medicine) {
                $tempTotal = $medicine->pivot->quantity * $medicine->pivot->price;
                $subTotalMedicines += $tempTotal;
            }
        }

        $grandTotal = $subTotalTreatments + $subTotalMedicines;

        if ($validatedData['patient_money'] >= $grandTotal) {
            $validatedData['status'] = 'Lunas';
        } else {
            $validatedData['status'] = 'Belum lunas';
        }

        if (!$appointment->payment) {

            // Create payment baru
            try {
                DB::transaction(function () use($appointment, $validatedData, $grandTotal) {
                    Payment::create([
                        'appointment_id' => $appointment->id,
                        'payment_type_id' => $validatedData['payment_type_id'],
                        'amount' => $grandTotal,
                        'operational_cost' => $validatedData['operational_cost'],
                        'lab_cost' => $validatedData['lab_cost'],
                        'patient_money' => $validatedData['patient_money'],
                        'doctor_percentage' => Doctor::find($appointment->doctor->id)->doctor_percentage,
                        'status' =>  $validatedData['status'],
                        'note' => $validatedData['note']
                    ]);
        
                    $appointmentData = ['next_appointment_date_time' => $validatedData['date_time']];

                    if ($appointment->status_id == 2) {
                        $appointmentData['status_id'] = 3;
                    }

                    $appointment->update($appointmentData);
                });
                
                return redirect('/appointments/'. $appointment->id)->with('success', 'Pemeriksaan berhasil!');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal');
            }
        } else {

            // Update payment yang sudah ada
            try {
                $payment = $appointment->payment;
                DB::transaction(function () use($payment, $appointment, $validatedData, $grandTotal) {
                    $payment->update([
                        'appointment_id' => $appointment->id,
                        'payment_type_id' => $validatedData['payment_type_id'],
                        'amount' => $grandTotal,
                        'operational_cost' => $validatedData['operational_cost'],
                        'lab_cost' => $validatedData['lab_cost'],
                        'patient_money' => $validatedData['patient_money'],
                        'doctor_percentage' => Doctor::find($appointment->doctor->id)->doctor_percentage,
                        'status' =>  $validatedData['status'],
                        'note' => $validatedData['note']
                    ]);
        
                    $appointment->update([
                        'next_appointment_date_time' => $validatedData['date_time']
                    ]);
                });

                return redirect('/appointments/'. $appointment->id)->with('success', 'Berhasil diedit!');   
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal');
            }
        }
        
    }
}
