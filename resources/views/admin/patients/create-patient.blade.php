@extends('admin.layouts.master')

@section('content')
<div class="min-h-full px-6 py-12 lg:px-8">
    <form class="flex flex-col" action="{{ route('patient.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex justify-between">
            <div class="flex flex-col w-full ml-2 justify-start">
                <!-- Name -->
                <x-app.input.all-inputs name="name" label="نام بیمار*" placeholder="کامبیز" />

                <!-- Name -->
                <x-app.input.all-inputs name="family" label="نام خانوادگی بیمار*" placeholder="دیرباز" />

                <!-- father -->
                <x-app.input.all-inputs name="father_name" label="نام پدر*" placeholder="علی" />

                <div class="flex items-center gap-2">
                    <!-- تبعه خارجی -->
                    <div class="flex justify-between items-center w-full">
                        <x-app.input.all-inputs type="checkbox" name="is_foreigner" label="تبعه خارجی" checked=""/>

                        <div id="show-passport-code" class="mt-4 hidden w-6/12">
                            <x-app.input.all-inputs name="passport_code" label="شماره پاسپورت بیمار" placeholder="شماره پاسپورت بیمار" />
                        </div>
                    </div>


                    <!-- کد ملی -->
                    <div id="show-national-code" class="w-full" >
                        <x-app.input.all-inputs name="national_code" type="number" label="کد ملی*" placeholder="1234567890" />
                    </div>
                </div>

                <!-- mobile number -->
                <x-app.input.all-inputs name="mobile" type="number" label="شماره موبایل*" placeholder="09123456789" />

                <!-- phone number -->
                <x-app.input.all-inputs name="phone" type="number" label="شماره منزل" placeholder="04131323334 (میتواند خالی باشد)" />
            </div>

            <div class="flex flex-col w-full mr-2 justify-between">
                <!-- address -->
                <x-app.input.all-inputs name="address" label="آدرس*" placeholder="آدرس مختصر" />

                <!-- birth_date -->
                <x-app.input.disabled-inputs name="birth_date" label="تاریخ تولد بیمار"  :value="empty($patient->birth_date) ? '-' : convertToJalali($patient->birth_date)" />

                <!-- جنسیت -->
                <x-app.input.all-inputs name="gender" type="select" label="جنسیت*" >
                    {{ optionDetails('gender')}}
                </x-app.input.all-inputs>

                <!-- تاهل -->
                <x-app.input.all-inputs name="relation_status" type="select" label="تاهل*" >
                    {{ optionDetails('relation_status')}}
                </x-app.input.all-inputs>

                <!-- انتخاب بیمه -->
                <x-app.input.all-inputs name="insurance_id" type="select" label="انتخاب بیمه*" >
                    @if (count(App\Models\Insurance::all()) == 0)
                        <option value="0">موردی یافت نشد</option>
                    @else
                        @foreach (App\Models\Insurance::all() as $insurance)
                            <option value="{{$insurance->id}}" {{ old('insurance_id') == $insurance->id ? 'selected' : ''}}>
                                {{ $insurance->title }}
                            </option>
                        @endforeach
                    @endif
                </x-app.input.all-inputs>

                <!-- شماره بیمه -->
                <x-app.input.all-inputs name="insurance_number" type="number" label="شماره بیمه" placeholder="165163" />

            </div>
        </div>
        <div class="flex justify-around mt-7">
            <x-app.button.add-btn >ایجاد بیمار جدید</x-app.add-btn>
            <x-app.button.cancel-btn :route="route('patient.index')">لغو عملیات</x-app.cancel-btn>
        </div>
    </form>
</div>

{{-- script for checkbox --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('is_foreigner');
        const passportCodeInput = document.getElementById('show-passport-code');
        const nationalCodeInput = document.getElementById('show-national-code')

        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                passportCodeInput.classList.remove('hidden');
                nationalCodeInput.classList.add('hidden');
            } else {
                passportCodeInput.classList.add('hidden');
                nationalCodeInput.classList.remove('hidden');
            }
        });
    });
</script>
@endsection
