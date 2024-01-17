<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\TreatmentType;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Treatment::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });

        $query->when($request->treatment_type, function ($query) use ($request) {
            return $query->where('treatment_type_id',  $request->treatment_type);
        });

        $per_page = $request->per_page ?? 10;
        
        session(['treatments_url' => request()->fullUrl()]);

        return view('treatments.index', [
            'treatments' => $query->orderBy('name', 'asc')->paginate($per_page)->appends($request->all()),
            'treatment_types' => TreatmentType::orderBy('name', 'asc')->get(),
            'per_page_options' => [10, 25, 50]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('treatments.create', [
            'treatment_types' =>TreatmentType::orderBy('name', 'asc')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'treatment_type_id' => 'required',
            'name' => 'required'
        ]);

        Treatment::create($validatedData);

        return redirect(session('treatments_url', '/treatments'))->with('success', 'Tindakan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Treatment $treatment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Treatment $treatment)
    {
        return view('treatments.edit', [
            'treatment' => $treatment,
            'treatment_types' => TreatmentType::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        $validatedData = $request->validate([
            'treatment_type_id' => 'required',
            'name' => 'required'
        ]);

        $treatment->update($validatedData);

        return redirect(session('treatments_url', '/treatments'))->with('success', 'Tindakan berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treatment $treatment)
    {
        try {
            Treatment::destroy($treatment->id);
            return redirect(session('treatments_url', '/treatments'))->with('success', 'Tindakan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('treatments_url', '/treatments'))->with('error', 'Tindakan gagal dihapus!');
        }
    }
}
