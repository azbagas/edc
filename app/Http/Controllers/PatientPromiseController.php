<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PatientPromise;

class PatientPromiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PatientPromise::query();

        $query->when($request->start_date, function ($query) use ($request) {
            $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date);
            return $query->whereBetween('date_time', [$start_date->startOfDay(), $end_date->endOfDay()]);
        }, function ($query) {
            return $query->whereDate('date_time', now()->today());
        });
        
        $query->when($request->status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        });

        $per_page = $request->per_page ?? 10;

        session(['patient_promises_url' => request()->fullUrl()]);

        return view('patient-promises.index', [
            'patientPromises' => $query->orderBy('date_time', 'desc')->paginate($per_page)->appends($request->all()),
            'statuses' => PatientPromise::STATUS,
            'per_page_options' => [10, 25, 50]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patient = Patient::findOrFail($request->patient);
        return view('patient-promises.create', ['patient' => $patient]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required',
            'date_time' => 'required|date_format:Y-m-d H:i',
            'note' => 'nullable'
        ]);

        PatientPromise::create($validatedData);

        return redirect('/patient-promises')->with('success', 'Janji berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientPromise $patientPromise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PatientPromise $patientPromise)
    {
        return view('patient-promises.edit', ['patientPromise' => $patientPromise]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatientPromise $patientPromise)
    {
        if ($request->batal) {
            $patientPromise->update(['status' => 'Batal']);
            return redirect(session('patient_promises_url'))->with('success', 'Janji berhasil dibatalkan!');
        }

        if ($request->selesai) {
            $patientPromise->update(['status' => 'Selesai']);
            return redirect(session('patient_promises_url'))->with('success', 'Janji berhasil diselesaikan!');
        }

        $validatedData = $request->validate([
            'patient_id' => 'required',
            'date_time' => 'required|date_format:Y-m-d H:i',
            'note' => 'nullable'
        ]);

        $patientPromise->update($validatedData);

        return redirect(session('patient_promises_url'))->with('success', 'Janji berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientPromise $patientPromise)
    {
        $patientPromise->delete();
        return redirect(session('patient_promises_url'))->with('success', 'Janji berhasil dihapus!');
    }
}
