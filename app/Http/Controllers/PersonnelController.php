<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\Rule as ModelsRule;
use App\Models\User;
use App\Rules\FullnameValidation;
use App\Rules\ImageValidation;
use App\Rules\MobileValidation;
use App\Rules\PasswordValidation;
use App\Rules\PersonnelCodeValidation;
use App\Rules\PersonnelValidation;
use App\Rules\RulesValidation;
use App\Rules\UsernameValidation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnels = Personnel::query();
        $users = User::query();

        // search actions
        if (request('search')) {
            $keyword = request('search');

            // find personnel using personnel_code
            if (in_array($keyword, Personnel::all()->pluck('personnel_code')->toArray())) {
                $personnels->where('personnel_code', 'like', "%$keyword%");
            } else if (in_array($keyword, ModelsRule::all()->pluck('persian_title')->toArray())) {
                // find personnel using rule
                $personnels->whereHas('user', function ($query) use ($keyword) {
                    $query->whereHas('rules', function ($ruleQuery) use ($keyword) {
                        $ruleQuery->where('persian_title', 'like', "%$keyword%");
                    });
                });
            } else {
                // find personnel via user relation
                $personnels->whereHas('user', function ($query) use ($keyword) {
                        $query->where('full_name', 'like', "%$keyword%")
                            ->orWhere('username', 'like', "%$keyword%")
                            ->orWhere('mobile', 'like', "%$keyword%");
                    }
                );
            }
        }

        $personnels = $personnels->latest()->paginate(10);

        return view('admin.personnels.all-personnels', [
            'personnels' => $personnels,
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.personnels.create-personnel');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // find user
        $user = User::findOrFail($request['personnel']);

        // validation
        $request->validate([
            'personnel' => [new PersonnelValidation],
            'personnel_code' => [new PersonnelCodeValidation($user)],
            'image_url' => [new ImageValidation]
        ]);

        // add uploaded image to storage and database
        // default personnel profile image
        $imageUrl = 'https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg';
        if ($request->hasFile('image_url')) {
            //TODO delete previous profile image if exist
            // Storage::disk('public')->delete();

            // get file name
            $image = $request->file('image_url')->getClientOriginalName();

            // saved path
            $path = $request->file('image_url')->storeAs('assets', $image ,'public');

            // Generate the full URL
            $imageUrl = url(Storage::url($path));
        }

        // create new personnel without profile image
        $personnel = Personnel::create([
            'full_name' => $user->full_name,
            'personnel_code' => $request['personnel_code'],
            'image_url' => $imageUrl,
            'user_id' => $user->id
        ]);

        // sync personnel_user table
        DB::table('personnel_user')->insert([
            'personnel_id' => $personnel->id,
            'user_id' => $user->id,
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('personnel.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel)
    {
        return view('admin.personnels.personnel', [
            'personnel' => $personnel
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel $personnel)
    {
        return view('admin.personnels.edit-personnel', [
            'personnel' => $personnel
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel $personnel)
    {
        // validation
        $request->validate([
            'full_name' => [new FullnameValidation],
            'username' => [new UsernameValidation($personnel->user)],
            'rules' => [new RulesValidation],
            'personnel_code' => [new PersonnelCodeValidation($personnel->user)],
            'mobile' => [new MobileValidation],
            'image_url' => [new ImageValidation]
        ]);

        // add uploaded image to storage and database
        // default personnel profile image
        $imageUrl = profileImageFunction($personnel->user);
        if ($request->hasFile('image_url')) {
            //TODO delete previous profile image if exist
            // Storage::disk('public')->delete();

            // get file name
            $image = $request->file('image_url')->getClientOriginalName();

            // saved path
            $path = $request->file('image_url')->storeAs('assets', $image ,'public');

            // Generate the full URL
            $imageUrl = url(Storage::url($path));
        }

        // update personnel
        $personnel->updateOrFail([
            'full_name' => $request['full_name'],
            'personnel_code' => $request['personnel_code'],
            'image_url' => $imageUrl
        ]);
        // update related details on users and rules table
        $personnel->user()->update([
            'full_name' => $request['full_name'],
            'username' => $request['username'],
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('personnel.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Personnel $personnel)
    {
        // delete only via admin or super admin
        // TODO add this functionality only to super admin not user itself
        if (Auth::user()->id == $personnel->user->id) {
            $personnel->deleteOrFail();

            // delete sessions
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // json success message
            response()->json(['message' => 'done'], 200);

            return redirect(route('personnel.index'));
        }

        return back();
    }
}
