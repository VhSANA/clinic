<?php

use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MedicalServicesController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PersonnelServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\UserController;
use App\Models\Insurance;
use App\Models\Patient;
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

// insurance
Route::resource('patient', PatientController::class);

// medicalservices
Route::resource('service', MedicalServicesController::class);

// rooms
Route::resource('room', RoomController::class);

// personnel-service
Route::resource('personnel-service', PersonnelServiceController::class);


require __DIR__.'/auth.php';
