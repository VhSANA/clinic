<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::query();

        // search actions
        if (request('search')) {
            $keyword = request('search');

            $patients
                ->where('full_name', 'like', "%$keyword%")
                ->orWhere('national_code', 'like', "%$keyword%")
                ->orWhere('mobile', 'like', "%$keyword%")
                ->orWhere('address', 'like', "%$keyword%")
                ->orWhere('passport_code', 'like', "%$keyword%");
        }

        $patients = $patients->latest()->paginate(10);

        return view('admin.patients.all-patients', [
            'patients' => $patients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.patients.create-patient');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $request->all();
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
