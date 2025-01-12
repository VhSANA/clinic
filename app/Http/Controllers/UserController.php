<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\Rule;
use App\Models\User;
use App\Rules\FullnameValidation;
use App\Rules\GenderValidation;
use App\Rules\ImageValidation;
use App\Rules\MobileValidation;
use App\Rules\NationalcodeValidation;
use App\Rules\PasswordValidation;
use App\Rules\RulesValidation;
use App\Rules\UsernameValidation;
use App\Rules\UsertitleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule as ValidationRule;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query();
        $rules = Rule::all();

        // TODO date filter add to search
        if (request('filter-radio')) {
            dd(request('filter-radio'));
        }

        // search actions
        if (request('search')) {
            $keyword = request('search');

            // find user using full_name, username or mobile
            if (! in_array($keyword, Rule::all()->pluck('persian_title')->toArray())) {
                $users->where('full_name', 'like', "%$keyword%")
                      ->orWhere('username', 'like', "%$keyword%")
                      ->orWhere('mobile', 'like', "%$keyword%");
            } else {
                // find user with rule relations
                $users->whereHas('rules', function ($query) use ($keyword) {
                    $query->where('persian_title', 'like', "%$keyword%");
                });
            }
        }

        $users = $users->latest()->paginate(10);
        return view('admin.users.all-users', [
            'users' => $users,
            'rules' => $rules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create-user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'username' => [new UsernameValidation($user)],
            'full_name' => [new FullnameValidation],
            'national_code' => [new NationalcodeValidation($user)],
            'mobile' => [new MobileValidation],
            'user_title' => [new UsertitleValidation],
            'rules' => [new RulesValidation],
            'gender' => [new GenderValidation],
            'password' => [new PasswordValidation],
        ]);

        $user = User::create([
            'username' => $request['username'],
            'full_name' => $request['full_name'],
            'national_code' => $request['national_code'],
            'mobile' => $request['mobile'],
            'user_title' => $request['user_title'],
            'gender' => $request['gender'],
            'password' => Hash::make($request['password']),
        ]);

        // sync data btw relations
        $user->rules()->sync($request['rules']);

        Auth::login($user);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('users.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.user', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit-user', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // validation
        $request->validate([
            'image_url' => [new ImageValidation],
            'full_name' => [new FullnameValidation],
            'username' => [new UsernameValidation($user)],
            'rules' => [new RulesValidation],
            'national_code' => [new NationalcodeValidation($user)],
            'mobile' => [new MobileValidation],
        ]);

        // passwrod validation if exists
        if ($request['password']) {
            $request->validate([
                'password' => [new PasswordValidation]
            ]);
        }

        // add uploaded image to storage and database
        if ($request->hasFile('image_url')) {
            //TODO delete previous profile image if exist
            // Storage::disk('public')->delete();

            // get file name
            $image = $request->file('image_url')->getClientOriginalName();

            // saved path
            $path = $request->file('image_url')->storeAs('assets', $image ,'public');

            // Generate the full URL
            $fullUrl = url(Storage::url($path));

            // Sync image_url
            $user->personnel()->update([
                'full_name' => $request['full_name'],
                'image_url' => $fullUrl,
            ]);
        }

        // sync rule
        if ($request['rules']) {
            $user->rules()->detach();
            $user->rules()->attach($request['rules']);
        }

        $user->update([
            'full_name' => $request['full_name'],
            'username' => $request['username'],
            'national_code' => $request['national_code'],
            'mobile' => $request['mobile'],
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // delete only via admin or super admin
        // TODO add this functionality only to super admin not user itself
        if (Auth::user()->id == $user->id) {
            $user->deleteOrFail();

            // delete sessions
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // json success message
            response()->json(['message' => 'done'], 200);

            return redirect(route('users.index'));
        }

        return back();
    }
}
