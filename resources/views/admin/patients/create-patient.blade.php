@extends('admin.layouts.master')

@section('content')
<div class="min-h-full px-6 py-12 lg:px-8">
    <form class="flex justify-between items-center" action="{{ route('patient.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col w-full ml-2 justify-start">
            <!-- Name -->
            <x-app.input.all-inputs name="full_name" label="نام و نام خانوادگی بیمار" placeholder="احد میرزایی" />

            <!-- father -->
            <x-app.input.all-inputs name="father_name" label="نام پدر" placeholder="علی" />

            <!-- کد ملی -->
            <x-app.input.all-inputs name="national_code" type="number" label="کد ملی" placeholder="1234567890" />

            <!-- mobile number -->
            <x-app.input.all-inputs name="mobile" type="number" label="شماره موبایل" placeholder="09123456789" />

            <!-- phone number -->
            <x-app.input.all-inputs name="phone" type="number" label="شماره منزل" placeholder="04131323334" />

            <!-- address -->
            <x-app.input.all-inputs name="address" label="آدرس" placeholder="آدرس مختصر" />
        </div>

        <div class="flex flex-col w-full mr-2 justify-between">
            <x-app.input.datetime-input />

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
                <option value="male">آقا</option>
                <option value="female">خانم</option>
            </x-app.input.all-inputs>


            <!-- Password -->
            <x-app.input.all-inputs name="password" type="password" label="رمزعبور" placeholder="رمزعبور خود را وارد کنید." />

            <!-- Confirm Password -->
            <x-app.input.all-inputs name="password_confirmation" type="password" label="تکرار رمزعبور" placeholder="تکرار رمزعبور خود را وارد کنید." />

            <div class="flex justify-evenly mt-7">
                <x-app.button.add-btn >ایجاد کاربر جدید</x-app.add-btn>
                <x-app.button.cancel-btn :route="route('users.index')">لغو ایجاد کاربر</x-app.cancel-btn>
            </div>
        </div>
    </form>
</div>
@endsection
