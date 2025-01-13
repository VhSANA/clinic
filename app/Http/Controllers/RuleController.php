<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\User;
use App\Rules\PersianTitleValidation;
use App\Rules\TitleValidation;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rules = Rule::query();
        $users = User::query();

        // search actions
        if (request('search')) {
            $keyword = request('search');

            $rules->where('title', 'like', "%$keyword%")
                ->orWhere('persian_title', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%");
        }

        $rules = $rules->latest()->paginate(10);

        return view('admin.rules.all-rules', [
            'rules' => $rules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rules.create-rule');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Rule $rule)
    {
        $request->validate([
            'title' => [new TitleValidation($rule)],
            'persian_title' => [new PersianTitleValidation($rule)],
            'description' => ['nullable', 'string']
        ]);

        // add to database
        Rule::create([
            'title' => $request['title'],
            'persian_title' => $request['persian_title'],
            'description' => $request['description'],
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('rule.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Rule $rule)
    {
        return view('admin.rules.rule', [
            'rule' => $rule
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rule $rule)
    {
        return view('admin.rules.edit-rule', [
            'rule' => $rule
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rule $rule)
    {
        $request->validate([
            'title' => [new TitleValidation($rule)],
            'persian_title' => [new PersianTitleValidation($rule)],
            'description' => ['nullable', 'string']
        ]);

        // add to database
        $rule->updateOrFail([
            'title' => $request['title'],
            'persian_title' => $request['persian_title'],
            'description' => $request['description'],
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('rule.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rule $rule)
    {
        // TODO add this feature only to super-admin
        $rule->deleteOrFail();

        return back();
    }
}
