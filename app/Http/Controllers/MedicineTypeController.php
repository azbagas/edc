<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MedicineTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MedicineType::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });

        $per_page = $request->per_page ?? 10;
        
        session(['medicine_types_url' => request()->fullUrl()]);

        return view('medicine-types.index', [
            'medicine_types' => $query->orderBy('name', 'asc')->paginate($per_page)->appends($request->all()),
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
        
        return view('medicine-types.create');
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

        MedicineType::create($validatedData);

        return redirect(session('medicine_types_url', '/medicine-types'))->with('success', 'Jenis obat berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicineType $medicineType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicineType $medicineType)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        return view('medicine-types.edit', [
            'medicine_type' => $medicineType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicineType $medicineType)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $validatedData = $request->validate([
            'name' => 'required'
        ]);

        $medicineType->update($validatedData);

        return redirect(session('medicine_types_url', '/medicine-types'))->with('success', 'Jenis obat berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicineType $medicineType)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        
        try {
            MedicineType::destroy($medicineType->id);
            return redirect(session('medicine_types_url', '/medicine-types'))->with('success', 'Jenis obat berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('medicine_types_url', '/medicine-types'))->with('error', 'Gagal menghapus jenis obat!');
        }
    }
}
