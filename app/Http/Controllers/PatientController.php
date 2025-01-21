<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Rules\AddressValidation;
use App\Rules\FirstnameValidation;
use App\Rules\GenderValidation;
use App\Rules\HomeNumberValidation;
use App\Rules\LasttnameValidation;
use App\Rules\MobileValidation;
use App\Rules\NationalcodeValidation;
use App\Rules\PatientNationalcodeValidation;
use App\Rules\PatientPassportcodeValidation;
use App\Rules\RelationStatusValidation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
                ->orWhere('address', 'like', "%$keyword%");
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
        // return $request->all();
        $request->validate([
            'name' => [new FirstnameValidation],
            'family' => [new LasttnameValidation],
            'father_name' => [new FirstnameValidation],
            'is_foreigner' => ['in:on,off'],
            'national_code' => [new PatientNationalcodeValidation($request)],
            'passport_code' => [new PatientPassportcodeValidation($request)],
            'mobile' => [new MobileValidation],
            'phone' => [new HomeNumberValidation],
            'address' => [new AddressValidation],
            'birth_date' => ['nullable'],
            'gender' => ['required',new GenderValidation],
            'relation_status' => ['required', new RelationStatusValidation],
            'insurance_id' => ['required', Rule::exists('insurances', 'id')],
            'insurance_number' => ['nullable', 'min:4'], // TODO min value of شماره بیمه
        ], [
            'gender.required' => 'جنسیت بیمار نمیتواند خالی باشد.',
            'relation_status.required' => 'وضعیت تاهل نمیتواند خالی باشد.',
            'insurance_id.required' => 'بیمه نمیتواند خالی باشد.',
            'insurance_id.exists' => 'بیمه انتخاب شده نامعتبر میباشد',
        ]);

        // add to databse
        switch ($request['is_foreigner']) {
            case 'on':
                Patient::create([
                    'name' => $request['name'],
                    'family' => $request['family'],
                    'full_name' => "{$request['name']} {$request['family']}",
                    'father_name' => $request['father_name'],
                    'national_code' => null,
                    'is_foreigner' => true,
                    'passport_code' => $request['passport_code'],
                    'mobile' => $request['mobile'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'birth_date' => $request['birth_date'],
                    'gender' => $request['gender'],
                    'relation_status' => $request['relation_status'],
                    'insurance_id' => $request['insurance_id'],
                    'insurance_number' => $request['insurance_number'],
                ]);
            break;
            case 'off':
                Patient::create([
                    'name' => $request['name'],
                    'family' => $request['family'],
                    'full_name' => "{$request['name']} {$request['family']}",
                    'father_name' => $request['father_name'],
                    'national_code' => $request['national_code'],
                    'is_foreigner' => false,
                    'passport_code' => null,
                    'mobile' => $request['mobile'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'birth_date' => $request['birth_date'],
                    'gender' => $request['gender'],
                    'relation_status' => $request['relation_status'],
                    'insurance_id' => $request['insurance_id'],
                    'insurance_number' => $request['insurance_number'],
                ]);
            break;
        }

        // redirect with json response
        return redirectWithJson(true, 'بیمار با موفقیت اضافه شد.',  [], 200, 'patient.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('admin.patients.patient', [
            'patient' => $patient
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('admin.patients.edit-patient', [
            'patient' => $patient
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        // return $request->all();
        $request->validate([
            'name' => [new FirstnameValidation],
            'family' => [new LasttnameValidation],
            'father_name' => [new FirstnameValidation],
            'is_foreigner' => ['in:on,off'],
            'national_code' => [new PatientNationalcodeValidation($request, $patient)],
            'passport_code' => [new PatientPassportcodeValidation($request, $patient)],
            'mobile' => [new MobileValidation],
            'phone' => [new HomeNumberValidation],
            'address' => [new AddressValidation],
            'birth_date' => ['nullable'],
            'gender' => ['required',new GenderValidation],
            'relation_status' => ['required', new RelationStatusValidation],
            'insurance_id' => ['required', Rule::exists('insurances', 'id')],
            'insurance_number' => ['nullable', 'min:4'], // TODO min value of شماره بیمه
        ], [
            'gender.required' => 'جنسیت بیمار نمیتواند خالی باشد.',
            'relation_status.required' => 'وضعیت تاهل نمیتواند خالی باشد.',
            'insurance_id.required' => 'بیمه نمیتواند خالی باشد.',
            'insurance_id.exists' => 'بیمه انتخاب شده نامعتبر میباشد',
        ]);

        // add to databse
        switch ($request['is_foreigner']) {
            case 'on':
                $patient->updateOrFail([
                    'name' => $request['name'],
                    'family' => $request['family'],
                    'full_name' => "{$request['name']} {$request['family']}",
                    'father_name' => $request['father_name'],
                    'national_code' => null,
                    'is_foreigner' => true,
                    'passport_code' => $request['passport_code'],
                    'mobile' => $request['mobile'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'birth_date' => $request['birth_date'],
                    'gender' => $request['gender'],
                    'relation_status' => $request['relation_status'],
                    'insurance_id' => $request['insurance_id'],
                    'insurance_number' => $request['insurance_number'],
                ]);
            break;
            case 'off':
                $patient->updateOrFail([
                    'name' => $request['name'],
                    'family' => $request['family'],
                    'full_name' => "{$request['name']} {$request['family']}",
                    'father_name' => $request['father_name'],
                    'national_code' => $request['national_code'],
                    'is_foreigner' => false,
                    'passport_code' => null,
                    'mobile' => $request['mobile'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'birth_date' => $request['birth_date'],
                    'gender' => $request['gender'],
                    'relation_status' => $request['relation_status'],
                    'insurance_id' => $request['insurance_id'],
                    'insurance_number' => $request['insurance_number'],
                ]);
            break;
        }

        // redirect with json response
        return redirectWithJson(true, 'بیمار با موفقیت ویرایش شد.',  [], 200, 'patient.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->deleteOrFail();
        return redirectWithJson(true, 'با موفقیت حذف شد', [], 200, 'patient.index');
    }
}
