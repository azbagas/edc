<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineType;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MedicineType::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->whereHas('medicines', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            });
        });

        $query->when($request->medicine_type, function ($query) use ($request) {
            return $query->where('id',  $request->medicine_type);
        });
        
        session(['medicines_url' => request()->fullUrl()]);

        return view('medicines.index', [
            'medicine_types' => $query->with(['medicines' => function ($query) {
                                    $query->orderBy('name', 'asc');
                                }])->orderBy('name', 'asc')->get(),
            'all_medicine_types' => MedicineType::orderBy('name', 'asc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicines.create', [
            'medicine_types' => MedicineType::orderBy('name', 'asc')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->price) {
            $request->merge(['price' => change_currency_format_to_decimal($request->price)]);
        }

        $validatedData = $request->validate([
            'medicine_type_id' => 'required',
            'name' => 'required',
            'dose' => 'nullable',
            'stock' => 'required|numeric',
            'unit' => 'required',
            'price' => 'required|numeric'
        ]);

        Medicine::create($validatedData);

        return redirect(session('medicines_url', '/medicines'))->with('success', 'Obat berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicine $medicine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', [
            'medicine' => $medicine,
            'medicine_types' => MedicineType::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medicine $medicine)
    {
        if ($request->price) {
            $request->merge(['price' => change_currency_format_to_decimal($request->price)]);
        }

        $validatedData = $request->validate([
            'medicine_type_id' => 'required',
            'name' => 'required',
            'dose' => 'nullable',
            'stock' => 'required|numeric',
            'unit' => 'required',
            'price' => 'required|numeric'
        ]);

        $medicine->update($validatedData);

        return redirect(session('medicines_url', '/medicines'))->with('success', 'Obat berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicine $medicine)
    {
        try {
            Medicine::destroy($medicine->id);
            return redirect(session('medicines_url', '/medicines'))->with('success', 'Obat berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('medicines_url', '/medicines'))->with('error', 'Obat gagal dihapus!');
        }
    }
}
