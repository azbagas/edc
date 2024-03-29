<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;
use App\Models\TreatmentType;
use Illuminate\Support\Facades\Gate;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TreatmentType::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->whereHas('treatments', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            });
        });

        $query->when($request->treatment_type, function ($query) use ($request) {
            return $query->where('id',  $request->treatment_type);
        });
        
        session(['treatments_url' => request()->fullUrl()]);

        return view('treatments.index', [
            // 'treatment_types' => $query->orderBy('name', 'asc')->with('treatments')->get(),
            'treatment_types' => $query->with(['treatments' => function ($query) {
                                    $query->orderBy('name', 'asc');
                                 }])->orderBy('name', 'asc')->get(),
            'all_treatment_types' => TreatmentType::orderBy('name', 'asc')->get()
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
        
        return view('treatments.create', [
            'treatment_types' =>TreatmentType::orderBy('name', 'asc')->get()
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
        if (!Gate::allows('admin')) {
            abort(403);
        }

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
        if (!Gate::allows('admin')) {
            abort(403);
        }

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
        if (!Gate::allows('admin')) {
            abort(403);
        }
        
        try {
            Treatment::destroy($treatment->id);
            return redirect(session('treatments_url', '/treatments'))->with('success', 'Tindakan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('treatments_url', '/treatments'))->with('error', 'Tindakan gagal dihapus!');
        }
    }
}
