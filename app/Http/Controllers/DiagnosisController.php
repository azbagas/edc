<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Disease;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Disease::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->whereHas('diagnoses', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%')
                      ->orWhere('diagnosis_code', 'like', '%' . $request->name . '%');
            });
        });

        $query->when($request->disease_id, function ($query) use ($request) {
            return $query->where('id',  $request->disease_id);
        });

        session(['diagnoses_url' => request()->fullUrl()]);

        return view('diagnoses.index', [
            'diseases' => $query->orderBy('disease_code', 'asc')->with('diagnoses')->get(),
            'all_diseases' => Disease::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('diagnoses.create', [
            'diseases' => Disease::orderBy('disease_code', 'asc')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'disease_id' => 'required',
            'diagnosis_code' => 'required|unique:diagnoses',
            'name' => 'required'
        ]);

        Diagnosis::create($validatedData);

        return redirect(session('diagnoses_url', '/diagnoses'))->with('success', 'Diagnosis berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Diagnosis $diagnosis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diagnosis $diagnosis)
    {
        return view('diagnoses.edit', [
            'diagnosis' => $diagnosis,
            'diseases' => Disease::orderBy('disease_code', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diagnosis $diagnosis)
    {
        $rules = [
            'disease_id' => 'required',
            'name' => 'required'
        ];

        if ($request->diagnosis_code != $diagnosis->diagnosis_code) {
            $rules['diagnosis_code'] = 'required|unique:diagnoses';
        }

        $validatedData = $request->validate($rules);

        $diagnosis->update($validatedData);

        return redirect(session('diagnoses_url', '/diagnoses'))->with('success', 'Diagnosis berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diagnosis $diagnosis)
    {
        try {
            Diagnosis::destroy($diagnosis->id);
            return redirect(session('diagnoses_url', '/diagnoses'))->with('success', 'Diagnosis berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('diagnoses_url', '/diagnoses'))->with('error', 'Diagnosis gagal dihapus!');
        }
    }
}
