<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
            'diseases' => $query->with(['diagnoses' => function ($query) {
                            $query->orderBy('diagnosis_code', 'asc');
                          }])->orderBy('disease_code', 'asc')->get(),
            'all_diseases' => Disease::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        
        return view('diagnoses.create', [
            'diseases' => Disease::orderBy('disease_code', 'asc')->get()
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
        if (!Gate::allows('admin')) {
            abort(403);
        }

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
        if (!Gate::allows('admin')) {
            abort(403);
        }

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
        if (!Gate::allows('admin')) {
            abort(403);
        }
        
        try {
            Diagnosis::destroy($diagnosis->id);
            return redirect(session('diagnoses_url', '/diagnoses'))->with('success', 'Diagnosis berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('diagnoses_url', '/diagnoses'))->with('error', 'Diagnosis gagal dihapus!');
        }
    }
}
