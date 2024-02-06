<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index() {
        return view('profile.index');
    }

    public function update(Request $request) {
        $user = User::findOrFail(Auth::user()->id);

        $rules = [];

        // Kalau username berubah
        if ($request->username != $user->username) {
            $rules['username'] = 'required|unique:users,username';
        }

        // Kalau email berubah
        if ($request->email != $user->email) {
            $rules['email'] = 'required|email:rfc,dns|unique:users,email';
        }

        // Kalau form password diisi, artinya ingin mengubah password
        $isChangingPassword = $request->current_password || $request->new_password || $request->new_password_confirmation; 
        if ($isChangingPassword) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:5|confirmed';
        }

        // Kalau upload foto profile
        $isChangingPhoto = $request->file('photo');
        if ($isChangingPhoto) {
            $rules['photo'] = 'nullable|image|file|max:5120';
        } 
        
        // ------ Validasi --------
        $validatedData = $request->validate($rules);
        
        if ($isChangingPassword) {
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return back()->with('error', 'Password saat ini salah');
            }

            $validatedData['password'] = Hash::make($validatedData['new_password']);
        }

        if ($isChangingPhoto) {
            // Delete foto lama jika bukan default photo
            if ($user->photo != 'images/profile-pictures/default-profile-picture.jpg') {
                Storage::delete($user->photo);
            }
            $validatedData['photo'] = $request->file('photo')->store('images/profile-pictures');
        }

        $user->update($validatedData);

        return redirect('/profile')->with('success', 'Profil berhasil diubah');

    }
}
