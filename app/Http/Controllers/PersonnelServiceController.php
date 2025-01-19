<?php

namespace App\Http\Controllers;

use App\Models\MedicalServices;
use App\Models\Personnel;
use App\Rules\EstimatedTimeValidation;
use App\Rules\PersonnelServiceValidation;
use App\Rules\PersonnelValidation;
use App\Rules\ServicePriceValidation;
use App\Rules\ServiceValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonnelServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get query of personnel
        $personnels = Personnel::query();

        // only show personnels with medical_service relations not all personels
        $personnels->whereHas('medicalservices');

        // search actions
        if (request('search')) {
            $keyword = request('search');

            // search base on personnel's full_name
            // TODO delete تومان or دقیقه from $keyword
            if (request('search')) {
                $keyword = request('search');

                $personnels->where(function ($query) use ($keyword) {
                    $query->where('full_name', 'like', "%$keyword%")
                      ->orWhereHas('medicalservices', function ($query) use ($keyword) {
                          $query->where('name', 'like', "%$keyword%")
                            ->orWhere('medical_services_personnel.estimated_service_time', 'like', "%$keyword%")
                            ->orWhere('medical_services_personnel.service_price', 'like', "%$keyword%");
                      });
                });
            }
        }

        $personnels = $personnels->with('medicalservices')->latest()->paginate(10);
// dd(count($personnels));
        return view('admin.personnel-service.all-personnel-service', [
            'personnels' => $personnels,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $personnels = Personnel::whereHas('user.rules', function ($query) {
            $query->where('title', 'doctor');
        })->get();
        $services = MedicalServices::all();

        return view('admin.personnel-service.create-personnel-service', [
            'personnels' => $personnels,
            'services' => $services,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'personnel' => ['required', new PersonnelServiceValidation],
            'service' => ['required', new ServiceValidation($request)],
            'estimated_service_time' => [new EstimatedTimeValidation],
            'service_price' => [new ServicePriceValidation],
        ], [
            'personnel.required' => 'انتخاب پرسنل الزامیست.',
            'service.required' => 'انتخاب خدمت درمانی الزامیست.',
        ]);

        // sync with database
        DB::table('medical_services_personnel')->insert([
            'medical_services_id' => $request['service'],
            'personnel_id' => $request['personnel'],
            'estimated_service_time' => $request['estimated_service_time'],
            'service_price' => $request['service_price'],
        ]);

        // json response
        response()->json(['message' => 'done'], 200);

        return redirect(route('personnel-service.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // find medical_service_personnel
        $personnel_service = DB::table('medical_services_personnel')->find($id);

        // find related personnel
        $personnel = Personnel::findOrFail($personnel_service->personnel_id);
        $service = MedicalServices::findOrFail($personnel_service->medical_services_id);

        return view('admin.personnel-service.personnel-service', [
            'personnel' => $personnel,
            'service' => $service,
            'personnel_service' => $personnel_service,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // get all of personnels who are doctor and all medical services
        $all_doctor_personnels = Personnel::whereHas('user.rules', function ($query) {
            $query->where('title', 'doctor');
        })->get();
        $all_services = MedicalServices::all();

        // find medical_service_personnel which is going to be edited
        $personnel_service = DB::table('medical_services_personnel')->find($id);

        // find related personnel and service
        $personnel = Personnel::findOrFail($personnel_service->personnel_id);
        $service = MedicalServices::findOrFail($personnel_service->medical_services_id);

        return view('admin.personnel-service.edit-personnel-service', [
            'personnel' => $personnel,
            'service' => $service,
            'all_doctor_personnels' => $all_doctor_personnels,
            'all_services' => $all_services,
            'personnel_service' => $personnel_service,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // validation
        $request->validate([
            'personnel' => ['required', new PersonnelServiceValidation],
            'service' => ['required', new ServiceValidation($request, $id)],
            'estimated_service_time' => [new EstimatedTimeValidation],
            'service_price' => [new ServicePriceValidation],
        ], [
            'personnel.required' => 'انتخاب پرسنل الزامیست.',
            'service.required' => 'انتخاب خدمت درمانی الزامیست.',
        ]);

        // sync with database
        DB::table('medical_services_personnel')->where('id', $id)->update([
            'medical_services_id' => $request['service'],
            'personnel_id' => $request['personnel'],
            'estimated_service_time' => $request['estimated_service_time'],
            'service_price' => $request['service_price'],
        ]);

        // json response
        response()->json(['message' => 'done'], 200);

        return redirect(route('personnel-service.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('medical_services_personnel')->delete($id);

        response()->json([
            'message' => 'done'
        ], 200);

        return redirect(route('personnel-service.index'));
    }
}
