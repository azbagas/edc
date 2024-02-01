<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        $query->whereHas('user', function ($query) use ($request) {
            $query->has('admin')->doesntHave('owner');
        });

        $query->when($request->name, function ($query) use ($request) {
            return $query->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            });
        });

        $per_page = $request->per_page ?? 10;
        
        session(['admins_url' => request()->fullUrl()]);

        return view('admins.index', [
            'admins' => $query->paginate($per_page)->appends($request->all()),
            'per_page_options' => [10, 25, 50]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.create');
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
            'email' => 'nullable|email:rfc,dns|unique:users,email',
            'phone' => 'required|numeric',
            'address' => 'required'
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

                Admin::create([
                    'user_id' => $user->id
                ]);

                // Attach roles
                $user->roles()->attach(Role::IS_ADMIN);
            });

            return redirect(session('admins_url', '/admins'))->with('success', 'Berhasil menambahkan admin!');
        } catch (\Exception $e) {
            return redirect(session('admins_url', '/admins'))->with('error', 'Gagal menambahkan admin!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admins.edit', [
            'admin' => $admin
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $rules = [
            'name' => 'required',
            'email' => 'nullable|email:rfc,dns',
            'phone' => 'required|numeric',
            'address' => 'required',
            'is_active' => 'required|boolean'
        ];

        if ($admin->user->email != $request->email) {
            $rules['email'] = 'nullable|email:rfc,dns|unique:users,email';
        }

        $validatedData = $request->validate($rules);

        try {
            DB::transaction(function() use($validatedData, $admin) {
                // Update user
                $user = $admin->user;
                
                $user->update([
                    'email' => $validatedData['email'],
                    'name' => $validatedData['name'],
                    'address' => $validatedData['address'],
                    'phone' => $validatedData['phone'],
                    'is_active' => $validatedData['is_active']
                ]);
            });

            return redirect(session('admins_url', '/admins'))->with('success', 'Admin berhasil diedit!');
        } catch (\Exception $e) {
            return redirect(session('admins_url', '/admins'))->with('error', 'Admin gagal diedit!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        try {
            DB::transaction(function () use ($admin) {
                $user = $admin->user;
                
                // Hapus admin
                Admin::destroy($admin->id);

                // Hapus role
                $user->roles()->detach();
    
                // Hapus User terkait
                if ($user) {
                    User::destroy($user->id);
                }
            });
    
            return redirect(session('admins_url', '/admins'))->with('success', 'Admin berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect(session('admins_url', '/admins'))->with('error', 'Admin gagal dihapus!');
        }
    }
}
