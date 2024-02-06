<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\MedicineTypeController;
use App\Http\Controllers\TreatmentTypeController;
use App\Http\Controllers\DependantDropdownController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PatientPromiseController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
    
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    
    Route::resource('/patients', PatientController::class);
    Route::resource('/appointments', AppointmentController::class);

    Route::get('/appointments/{appointment}/examination', [AppointmentController::class, 'examination']);
    Route::put('/appointments/{appointment}/examination', [AppointmentController::class, 'examination_update']);
    
    Route::get('/appointments/{appointment}/payment', [AppointmentController::class, 'payment']);
    Route::put('/appointments/{appointment}/payment', [AppointmentController::class, 'payment_update']);

    
    
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
    Route::resource('/medicine-types', MedicineTypeController::class)->except([
        'show'
    ]);
    Route::resource('/medicines', MedicineController::class)->except([
        'show'
    ]);
    Route::resource('/patient-promises', PatientPromiseController::class)->except([
        'show'
    ]);
    
    
    Route::middleware(['owner'])->group(function() {
        Route::get('/recap/daily', [RecapController::class, 'recapDaily'])->name('recap-daily');
        Route::get('/recap/monthly', [RecapController::class, 'recapMonthly'])->name('recap-monthly');
        Route::resource('/admins', AdminController::class)->except([
            'show'
        ]);
    });

    Route::middleware(['admin'])->group(function() {
        Route::resource('/doctors', DoctorController::class)->except([
            'show'
        ]);

        Route::resource('/assistants', AssistantController::class)->except([
            'show'
        ]);

        Route::resource('/expenses', ExpenseController::class)->except([
            'show'
        ]);

        Route::resource('/income', IncomeController::class)->only([
            'index'
        ]);

        Route::resource('/payment-types', PaymentTypeController::class)->except([
            'show'
        ]);

        Route::get('/reports/community-health-center/daily', [ReportController::class, 'communityHealthCenterDaily'])->name('community-health-center-daily');
        Route::get('/reports/community-health-center/monthly', [ReportController::class, 'communityHealthCenterMonthly'])->name('community-health-center-monthly');
    });

    
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

    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');
});

