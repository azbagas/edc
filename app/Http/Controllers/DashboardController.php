<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $query = Appointment::query();

        $query->whereDate('date_time', now()->today());

        if (Auth::user()->roles->contains(Role::IS_DOCTOR)) {
            $query->where('doctor_id', Auth::user()->doctor->id);
        }

        return view('dashboard.index', [
            'totalPatients' => Patient::count(),
            'todayAppointments' => $query->count()
        ]);
    }
}
