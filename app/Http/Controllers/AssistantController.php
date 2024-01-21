<?php

namespace App\Http\Controllers;

use App\Models\Assistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssistantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Assistant::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });

        $per_page = $request->per_page ?? 10;
        
        session(['assistants_url' => request()->fullUrl()]);

        return view('assistants.index', [
            'assistants' => $query->paginate($per_page)->appends($request->all()),
            'per_page_options' => [10, 25, 50]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assistants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required'
        ]);

        try {
            Assistant::create([
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'phone' => $validatedData['phone'],
            ]);

            return redirect(session('assistants_url', '/assistants'))->with('success', 'Berhasil menambahkan asisten!');
        } catch (\Exception $e) {
            return redirect(session('assistants_url', '/assistants'))->with('error', 'Gagal menambahkan asisten!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Assistant $assistant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assistant $assistant)
    {
        return view('assistants.edit', [
            'assistant' => $assistant
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assistant $assistant)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'is_active' => 'required|boolean'
        ]);

        try {
            $assistant->update([
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'phone' => $validatedData['phone'],
                'is_active' => $validatedData['is_active']
            ]);

            return redirect(session('assistants_url', '/assistants'))->with('success', 'Asisten berhasil diedit!');
        } catch (\Exception $e) {
            return redirect(session('assistants_url', '/assistants'))->with('error', 'Asisten gagal diedit!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assistant $assistant)
    {
        try {
            Assistant::destroy($assistant->id);
    
            return redirect(session('assistants_url', '/assistants'))->with('success', 'Asisten berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('assistants_url', '/assistants'))->with('error', 'Asisten gagal dihapus!');
        }
    }
}
