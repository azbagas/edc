<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TreatmentType;
use Illuminate\Support\Facades\Gate;

class TreatmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TreatmentType::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });

        $per_page = $request->per_page ?? 10;
        
        session(['treatment_types_url' => request()->fullUrl()]);

        return view('treatment-types.index', [
            'treatment_types' => $query->orderBy('name', 'asc')->paginate($per_page)->appends($request->all()),
            'per_page_options' => [10, 25, 50]
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
        
        return view('treatment-types.create');
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
            'name' => 'required'
        ]);

        TreatmentType::create($validatedData);

        return redirect(session('treatment_types_url', '/treatment-types'))->with('success', 'Jenis tindakan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TreatmentType $treatmentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TreatmentType $treatmentType)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        return view('treatment-types.edit', [
            'treatment_type' => $treatmentType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TreatmentType $treatmentType)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $validatedData = $request->validate([
            'name' => 'required'
        ]);

        $treatmentType->update($validatedData);

        return redirect(session('treatment_types_url', '/treatment-types'))->with('success', 'Jenis tindakan berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TreatmentType $treatmentType)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        
        try {
            TreatmentType::destroy($treatmentType->id);
            return redirect(session('treatment_types_url', '/treatment-types'))->with('success', 'Jenis tindakan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('treatment_types_url', '/treatment-types'))->with('error', 'Gagal menghapus jenis tindakan!');
        }
    }
}
