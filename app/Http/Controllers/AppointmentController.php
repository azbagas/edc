<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Status;
use App\Models\Disease;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Medicine;
use App\Models\Assistant;
use App\Models\Appointment;
use App\Models\PaymentType;
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

        $query->when($request->date, function ($query) use ($request) {
            if ($request->date == 'yesterday') {
                return $query->whereDate('created_at', now()->subDay());
            } elseif ($request->date == 'thisMonth') {
                return $query->whereYear('created_at', now()->year)
                             ->whereMonth('created_at', now()->month);
            } elseif ($request->date == 'sevenDaysBefore') {
                return $query->whereBetween('created_at', [now()->subDays(7), now()->endOfDay()]);
            } elseif ($request->date == 'allTime') {
                return;
            }
        }, function ($query) {
            return $query->whereDate('created_at', now()->today());
        });

        Session::put('appointments_url', request()->fullUrl());

        return view('appointments.index', [
            'appointments' => $query->latest()->paginate(10)->appends($request->all()),
            'doctors' => Doctor::all(),
            'statuses' => Status::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Patient $patient)
    {
        // dd(Auth::user()->roles);
        return view('appointments.create', [
            'patient' => $patient,
            'doctors' => Doctor::all(),
            'assistants' => Assistant::all()
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
    public function show(Appointment $appointment)
    {
        // if (!$appointment->payment) {
        //     return redirect()->back()->with('info', 'Belum diperiksa');
        // }

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
            'doctors' => Doctor::all(),
            'assistants' => Assistant::all()
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
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'assistant_id' => 'required',
            'complaint' => 'required',
        ]);

        $appointment->update($validatedData);

        return redirect($request->fromUrl)->with('success', 'Pertemuan berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
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
            'appointment' => $appointment
        ]);
    }

    public function examination_update(Request $request, Appointment $appointment)
    {
        // dd($request->all());
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
                // Masukkan diagnose
                if ($request->diagnose) {
                    $requestDiagnoses = $request->diagnose;
                    $requestDiagnoseNotes = $request->diagnose_note;
                    $diagnoseIn = [];
                    
                    foreach ($requestDiagnoses as $i => $diagnoseId) {
                        $diagnoseIn[$diagnoseId] = ['note' => $requestDiagnoseNotes[$i]];
                    }

                    $appointment->diagnoses()->sync($diagnoseIn);
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
        $operationalCost = $request->operational_cost;
        $operationalCost = change_currency_format_to_decimal($operationalCost);
        $request->merge(['operational_cost' => $operationalCost]);

        $validatedData = $request->validate([
            'payment_type_id' => 'required',
            'operational_cost' => 'required'
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

        if (!$appointment->payment) {
            Payment::create([
                'appointment_id' => $appointment->id,
                'payment_type_id' => $validatedData['payment_type_id'],
                'amount' => $grandTotal,
                'operational_cost' => $validatedData['operational_cost'],
                'doctor_percentage' => Doctor::find($appointment->doctor->id)->doctor_percentage,
                'status' => 'Lunas'
            ]);
        } else {
            $payment = $appointment->payment;
            $payment->update([
                'appointment_id' => $appointment->id,
                'payment_type_id' => $validatedData['payment_type_id'],
                'amount' => $grandTotal,
                'operational_cost' => $validatedData['operational_cost'],
                'doctor_percentage' => Doctor::find($appointment->doctor->id)->doctor_percentage,
                'status' => 'Lunas'
            ]);
        }

        if ($appointment->status_id == 2) {
            $appointment->update([
                'status_id' => 3
            ]);
        }

        return redirect('/appointments/'. $appointment->id)->with('success', 'Pemeriksaan berhasil');
    }
}
