@extends('admin.layouts.master')

@section('content')
<div class="min-h-full px-6 py-12 lg:px-8">
    <form class="flex justify-between items-center" action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="flex flex-col w-full ml-2 justify-start">
            <!-- Name -->
            <x-app.input.all-inputs name="full_name" label="نام و نام خانوادگی کاربر" placeholder="سپهر برنا" />

            <!-- username -->
            <x-app.input.all-inputs name="username" label="نام کاربری" placeholder="sepehrbr, Sepehr99, sepher@99" />

            <!-- کد ملی -->
            <x-app.input.all-inputs name="national_code" type="number" label="کد ملی" placeholder="1234567890" />

            <!-- mobile number -->
            <x-app.input.all-inputs name="mobile" type="number" label="شماره موبایل" placeholder="09123456789" />

            <!-- عنوان پرسنل -->
            <x-app.input.all-inputs name="user_title" label="عنوان کاربر" placeholder="جناب آقای، سرکار خانم و ..." />
        </div>

        <div class="flex flex-col w-full mr-2 justify-between">
            {{-- سمت و مقام --}}
            <x-app.input.all-inputs name="rules" type="select" label="سمت کاربر" >
                @if (count(App\Models\Rule::all()) == 0)
                    <option value="0">موردی یافت نشد</option>
                @else
                    @foreach (App\Models\Rule::all() as $rule)
                        <option value="{{ $rule->id }}">
                            {{ $rule->persian_title }} - {{ $rule->title }}
                        </option>
                    @endforeach
                @endif
            </x-app.input.all-inputs>

            <!-- جنسیت -->
            <x-app.input.all-inputs name="gender" type="select" label="جنسیت" >
                {{ optionDetails('gender')}}
            </x-app.input.all-inputs>


            <!-- Password -->
            <x-app.input.all-inputs name="password" type="password" label="رمزعبور" placeholder="رمزعبور خود را وارد کنید." />

            <!-- Confirm Password -->
            <x-app.input.all-inputs name="password_confirmation" type="password" label="تکرار رمزعبور" placeholder="تکرار رمزعبور خود را وارد کنید." />

            {{-- عملیات ایجاد و لغو --}}
            <x-app.button.button-groups.create-handlers :cancel_route="route('users.index')" />
        </div>
    </form>
</div>
@endsection
