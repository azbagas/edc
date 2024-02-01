<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Disease::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%')
                        ->orWhere('disease_code', 'like', '%' . $request->name . '%');
        });

        $per_page = $request->per_page ?? 10;
        
        session(['diseases_url' => request()->fullUrl()]);

        return view('diseases.index', [
            'diseases' => $query->orderBy('disease_code', 'asc')->paginate($per_page)->appends($request->all()),
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
        
        return view('diseases.create');
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
            'disease_code' => 'required|unique:diseases',
            'name' => 'required'
        ]);

        Disease::create($validatedData);

        return redirect(session('diseases_url', '/diseases'))->with('success', 'Penyakit berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Disease $disease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disease $disease)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        return view('diseases.edit', [
            'disease' => $disease
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disease $disease)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $validatedData = $request->validate([
            'disease_code' => 'required',
            'name' => 'required'
        ]);

        $disease->update($validatedData);

        return redirect(session('diseases_url', '/diseases'))->with('success', 'Penyakit berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disease $disease)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        
        try {
            Disease::destroy($disease->id);
            return redirect(session('diseases_url', '/diseases'))->with('success', 'Penyakit berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('diseases_url', '/diseases'))->with('error', 'Gagal menghapus penyakit!');
        }
    }
}
