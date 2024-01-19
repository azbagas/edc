<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Doctor::query();

        $query->when($request->name, function ($query) use ($request) {
            return $query->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            });
        });

        $per_page = $request->per_page ?? 10;
        
        session(['doctors_url' => request()->fullUrl()]);

        return view('doctors.index', [
            'doctors' => $query->paginate($per_page)->appends($request->all()),
            'per_page_options' => [10, 25, 50]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|alpha_num:ascii|unique:users,username|min:3',
            'password' => 'required|min:5|confirmed',
            'name' => 'required',
            'sip' => 'required',
            'email' => 'nullable|email:rfc,dns|unique:users,email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'doctor_percentage' => 'required|numeric|min:0|max:1'
        ]);

        try {
            DB::transaction(function() use($validatedData) {
                // Create user
                $user = User::create([
                            'username' => $validatedData['username'],
                            'password' => Hash::make($validatedData['password']),
                            'email' => $validatedData['email'],
                            'name' => $validatedData['name'],
                            'address' => $validatedData['address'],
                            'phone' => $validatedData['phone'],
                        ]);

                Doctor::create([
                    'user_id' => $user->id,
                    'sip' => $validatedData['sip'],
                    'doctor_percentage' => $validatedData['doctor_percentage']
                ]);
            });

            return redirect(session('doctors_url', '/doctors'))->with('success', 'Berhasil menambahkan dokter!');
        } catch (\Exception $e) {
            return redirect(session('doctors_url', '/doctors'))->with('error', 'Gagal menambahkan dokter!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', [
            'doctor' => $doctor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $rules = [
            'name' => 'required',
            'sip' => 'required',
            'email' => 'nullable|email:rfc,dns',
            'phone' => 'required|numeric',
            'address' => 'required',
            'doctor_percentage' => 'required|numeric|min:0|max:1',
            'is_active' => 'required|boolean'
        ];

        if ($doctor->user->email != $request->email) {
            $rules['email'] = 'nullable|email:rfc,dns|unique:users,email';
        }

        $validatedData = $request->validate($rules);

        try {
            DB::transaction(function() use($validatedData, $doctor) {
                // Update user
                $user = $doctor->user;
                
                $user->update([
                    'email' => $validatedData['email'],
                    'name' => $validatedData['name'],
                    'address' => $validatedData['address'],
                    'phone' => $validatedData['phone'],
                    'is_active' => $validatedData['is_active']
                ]);

                // Update doctor
                $doctor->update([
                    'sip' => $validatedData['sip'],
                    'doctor_percentage' => $validatedData['doctor_percentage']
                ]);

            });

            return redirect(session('doctors_url', '/doctors'))->with('success', 'Dokter berhasil diedit!');
        } catch (\Exception $e) {
            return redirect(session('doctors_url', '/doctors'))->with('error', 'Dokter gagal diedit!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        try {
            DB::transaction(function () use ($doctor) {
                $user = $doctor->user;
                
                // Hapus Doctor
                Doctor::destroy($doctor->id);
    
                // Hapus User terkait
                if ($user) {
                    User::destroy($user->id);
                }
            });
    
            return redirect(session('doctors_url', '/doctors'))->with('success', 'Dokter berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('doctors_url', '/doctors'))->with('error', 'Dokter gagal dihapus!');
        }
    }
}
