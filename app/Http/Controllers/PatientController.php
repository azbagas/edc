<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        $query->when($request->id, function ($query) use ($request) {
            return $query->where('id', 'like', $request->id . '%');
        });

        $query->when($request->name, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        });

        $query->when($request->address, function ($query) use ($request) {
            return $query->where('address', 'like', '%' . $request->address . '%');
        });

        $per_page = $request->per_page ?? 10;
        
        session(['patients_url' => request()->fullUrl()]);

        return view('patients.index', [
            'patients' => $query->orderByDesc('id')->paginate($per_page)->appends($request->all()),
            'newPatientId' => Patient::max('id') + 1,
            'per_page_options' => [10, 25, 50]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create', ['newPatientId' => Patient::max('id') + 1]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'date_of_birth' => 'required|date_format:d-m-Y',
            'gender' => 'required',
            'phone' => 'nullable|numeric',
            'address' => 'required'
        ]);

        Patient::create($validatedData);

        return redirect('/patients')->with('success', 'Pasien berhasil dibuat');

        // $validatedData = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'date_of_birth' => 'required',
        //     'gender' => 'required',
        //     'phone' => 'nullable',
        //     'address' => 'required'
        // ])->validateWithBag('create');

        // Patient::create($validatedData);

        // return redirect('/patients')->with('success', 'Pasien berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('patients.show', [
            'patient' => Patient::find($id),
            'appointments' => Appointment::where('patient_id', $id)->orderByDesc('created_at')->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('patients.edit', [
            'patient' => Patient::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'date_of_birth' => 'required|date_format:d-m-Y',
            'gender' => 'required',
            'phone' => 'nullable|numeric',
            'address' => 'required'
        ]);

        Patient::find($id)->update($validatedData);

        return redirect($request->fromUrl)->with('success', 'Pasien berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Patient::destroy($id);
            return redirect('/patients')->with('success', 'Pasien berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pasien');
        }
    }
}
