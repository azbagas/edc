<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Assistant;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                return $query->whereBetween('created_at', [now()->subDays(7), now()->today()]);
            }
        }, function ($query) {
            return $query->whereDate('created_at', now()->today());
        });

        return view('appointments.index', [
            'appointments' => $query->paginate(10)->appends($request->all()),
            'doctors' => Doctor::all(),
            'statuses' => Status::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Patient $patient)
    {
        // dd(Auth::user()->admin->id);
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
        $validatedData = $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'assistant_id' => 'required',
            'complaint' => 'required',
        ]);

        if (Auth::user()->role->name != 'admin') {
            return back()->with('error', 'Anda bukan admin!');
        }

        // $validatedData['admin_id'] = 

        // $validatedData['']

        // Appointment::create()

        // Patient::create($validatedData);

        // return redirect('/patients')->with('success', 'Pasien berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
