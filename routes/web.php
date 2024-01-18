<?php

use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TreatmentTypeController;
use App\Http\Controllers\DependantDropdownController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });
    
    Route::get('/dashboard', function () {
        return view('dashboard.index', [
            'totalPatients' => Patient::count(),
            'todayAppointments' => Appointment::whereDate('created_at', now()->today())->count()
        ]);
    });
    
    Route::resource('/patients', PatientController::class);
    Route::resource('/appointments', AppointmentController::class);
    Route::get('/appointments/create/{patient}', [AppointmentController::class, 'create']);

    Route::get('/appointments/{appointment}/examination', [AppointmentController::class, 'examination']);
    Route::put('/appointments/{appointment}/examination', [AppointmentController::class, 'examination_update']);
    
    Route::get('/appointments/{appointment}/payment', [AppointmentController::class, 'payment']);
    Route::put('/appointments/{appointment}/payment', [AppointmentController::class, 'payment_update']);

    Route::resource('/income', IncomeController::class)->only([
        'index'
    ]);
    Route::resource('/expenses', ExpenseController::class)->except([
        'show'
    ]);
    Route::resource('/treatment-types', TreatmentTypeController::class)->except([
        'show'
    ]);
    Route::resource('/treatments', TreatmentController::class)->except([
        'show'
    ]);
    Route::resource('/diseases', DiseaseController::class)->except([
        'show'
    ]);
    Route::resource('/diagnoses', DiagnosisController::class)->except([
        'show'
    ]);




    
    Route::get('/get-diseases', [DependantDropdownController::class, 'getDiseases'])->name('getDiseases');
    Route::get('/get-treatment-types', [DependantDropdownController::class, 'getTreatmentTypes'])->name('getTreatmentTypes');
    Route::get('/get-medicine-types', [DependantDropdownController::class, 'getMedicineTypes'])->name('getMedicineTypes');
    
    Route::get('/get-diagnoses', [DependantDropdownController::class, 'getDiagnoses'])->name('getDiagnoses');
    Route::get('/get-treatments', [DependantDropdownController::class, 'getTreatments'])->name('getTreatments');
    Route::get('/get-medicines', [DependantDropdownController::class, 'getMedicines'])->name('getMedicines');

    Route::post('/logout', [LoginController::class, 'logout']);
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

