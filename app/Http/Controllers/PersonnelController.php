<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\User;
use App\Rules\PasswordValidation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
        $request->validate([
            'username' => ['required', 'string', 'min:5', 'max:20', 'regex:/^[a-zA-Z0-9_\-\$\%\#\@\!\*\&\(\)]{3,20}$/', Rule::unique('users', 'username')],
            'full_name' => [
                'required',
                'string',
                'regex:/^[\x{0600}-\x{06FF}]+[\s]+[\x{0600}-\x{06FF}]+$/u',
                'min:3',
                'max:255'
            ],
            'national_code' => [
                'required',
                'string',
                'size:10',
                'regex:/^(?!(\d)\1{9})\d{10}$/',
                Rule::unique('users', 'national_code')
            ],
            'mobile' => ['required', 'regex:/^(?:0|98|\+98|\+980|0098|098|00980)?(9\d{9})$/'],
            'user_title' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\x{0600}-\x{06FF}\s]+$/u'],
            'rules' => ['required', 'string', 'exists:rules,id'],
            'gender' => ['required', 'string', 'in:male,female'],
            'password' => ['required', 'confirmed', new PasswordValidation],
        ], [
            'username.required' => 'وارد کردن نام کاربری الزامی است.',
            'username.min' => 'نام کاربری حداقل باید شامل 5 کاراکتر باشد.',
            'username.max' => 'نام کاربری نمیتواند بیشتر از 20 کاراکتر باشد.',
            'username.regex' => 'نام کاربری فقط میتواند شامل حروف بزرگ و کوچک انگلیسی و نماد ها باشد.',
            'username.unique' => 'این نام کاربری قبلا ثبت شده است.',
            'full_name.required' => 'وارد کردن نام و نام خانوداگی الزامی است.',
            'full_name.min' => 'نام و نام خانوداگی حداقل باید شامل 5 کاراکتر باشد.',
            'full_name.regex' => 'نام کامل باید شامل نام و نام خانوادگی باشد و فقط با حروف فارسی نوشته شده باشد.',
            'national_code.required' => 'وارد نمودن کد ملی الزامیست.',
            'national_code.size' => 'کد ملی فقط باید شامل 10 رقم باشد.',
            'national_code.regex' => 'کد ملی وارد شده معتبر نیست.',
            'mobile.required' => 'وارد نمودن شماره موبایل الزامیست.',
            'mobile.regex' => 'شماره موبایل وارد شده معتبر نیست.',
            'user_title.required' => 'وارد نمودن عنوان پرسنل الزامیست.',
            'user_title.min' => 'عنوان پرسنل حداقل باید سه کاراکتر باشد.',
            'user_title.max' => 'عنوان پرسنل وارد شده بیش از حد مجاز میباشد.',
            'user_title.regex' => 'عنوان پرسنل فقط باید شامل حروف فارسی باشد',
            'rules.required' => 'انتخاب مقام برای پرسنل الزامیست.',
            'gender.required' => 'انتخاب جنسیت الزامیست.',
            'password.required' => 'وارد نمودن رمزعبور الزامیست.',
            'password.confirmed' => 'تکرار رمز عبور با خود رمزعبور مطابقت ندارد.',
            'password.min' => 'رمز عبور باید حداقل شامل 5 کاراکتر باشد.',
            'password.letters' => 'رمز عبور باید حداقل شامل 1 حرف انگلیسی باشد.',
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

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel $personnel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel $personnel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personnel $personnel)
    {
        //
    }
}
