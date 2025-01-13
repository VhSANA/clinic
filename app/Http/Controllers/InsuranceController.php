<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Rules\ImageValidation;
use App\Rules\InsuranceTitleValidation;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insurances = Insurance::query();

        // search actions
        if (request('search')) {
            $keyword = request('search');

            $insurances->where('title', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%");
        }

        $insurances = $insurances->latest()->paginate(10);

        return view('admin.insurance.all-insurance', [
            'insurances' => $insurances,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.insurance.create-insurance');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Insurance $insurance)
    {
        $request->validate([
            'title' => [new InsuranceTitleValidation($insurance)],
            'description' => ['nullable', 'string'],
        ]);

        // add to database
        Insurance::create([
            'title' => $request['title'],
            'description' => $request['description'],
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('insurance.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        return view('admin.insurance.insurance', [
            'insurance' => $insurance
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        return view('admin.insurance.edit-insurance', [
            'insurance' => $insurance
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insurance $insurance)
    {
        $request->validate([
            'title' => [new InsuranceTitleValidation($insurance)],
            'description' => ['nullable', 'string']
        ]);

        // add to database
        $insurance->updateOrFail([
            'title' => $request['title'],
            'description' => $request['description'],
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('insurance.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        $insurance->deleteOrFail();

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('insurance.index'));
    }
}
