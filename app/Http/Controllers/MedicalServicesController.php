<?php

namespace App\Http\Controllers;

use App\Models\MedicalServices;
use App\Rules\DescriptionValidation;
use App\Rules\MedicalServiceValidation;
use Illuminate\Http\Request;

class MedicalServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = MedicalServices::query();

        // search actions
        if (request('search')) {
            $keyword = request('search');

            if (str_contains($keyword, 'خیر')) {
                // show disabled services
                $services->where('display_in_list', 'like', 0);
            } else if (str_contains($keyword, 'بله')) {
                // show enabled services
                $services->where('display_in_list', 'like', 1);
            } else {
                // show searched services
                $services
                ->where('name', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%");
            }
        }

        $services = $services->latest()->paginate(10);

        return view('admin.medical-services.all-services', [
            'services' => $services,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.medical-services.create-service');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, MedicalServices $service)
    {
        $request->validate([
            'name' => [new MedicalServiceValidation($service)],
            'description' => [new DescriptionValidation],
            'display_in_list' => ['in:on,null']
        ]);

        // add to database
        MedicalServices::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'display_in_list' => $request['display_in_list'] == 'on' ? true : false,
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('service.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalServices $service)
    {
        return view('admin.medical-services.service', [
            'service' => $service
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalServices $service)
    {
        return view('admin.medical-services.edit-service', [
            'service' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalServices $service)
    {
        $request->validate([
            'name' => [new MedicalServiceValidation($service)],
            'description' => [new DescriptionValidation],
            'display_in_list' => ['in:on,null']
        ]);

        // update database
        $service->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'display_in_list' => $request['display_in_list'] == 'on' ? true : false,
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('service.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalServices $service)
    {
        $service->deleteOrFail();
        return redirect()->route('service.index');
    }
}
