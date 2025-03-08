<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MedicalServicesController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PersonnelServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Models\Calendar;
use App\Models\Insurance;
use App\Models\Patient;
use App\Models\ScheduleDate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/a', function () {
    $user = Auth::loginUsingId(1);
    return 'logged in as ' . $user->full_name ;
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'user' => Auth::user()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// users
Route::resource('users', UserController::class);

// personnel
Route::resource('personnel', PersonnelController::class);

// rule
Route::resource('rule', RuleController::class);

// insurance
Route::resource('insurance', InsuranceController::class);

// patient
Route::resource('patient', PatientController::class);

// medicalservices
Route::resource('service', MedicalServicesController::class);

// rooms
Route::resource('room', RoomController::class);

// personnel-service
Route::resource('personnel-service', PersonnelServiceController::class);

// calendar
Route::resource('calendar', CalendarController::class)->except(['create', 'show', 'edit']);

// schedule
Route::resource('schedule', ScheduleController::class)->except(['create', 'show', 'edit']);
Route::post('/schedule/paste', [ScheduleController::class, 'paste'])->name('schedule.paste');

// appointments
Route::prefix('appointments')->controller(AppointmentController::class)->group(function () {
    Route::get('appointment', 'appointment')->name('appointments.appointment');
    Route::post('appointment', 'store')->name('appointments.store');

    // appointmentes list
    Route::get('appointments/list', 'patientsList')->name('appointments.patients.list');
    Route::post('appointments/list', 'patientsListStore')->name('appointments.patients.list.store');
    Route::put('appointments/list/{appointment}', 'cancelAppointment')->name('appointments.patients.list.cancel');
    Route::delete('appointments/list/{appointment}', 'deleteAppointment')->name('appointments.patients.list.destory');

    // invoice
    Route::post('appointments/list/{invoice}', 'payments')->name('appointment.payments');
    // print invoice
    Route::get('appointments/list/print/{appointment}', 'printInvoice')->name('appointment.print');
});

require __DIR__.'/auth.php';
