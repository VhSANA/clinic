@extends('admin.layouts.master')

@section('content')
<!-- Card start -->
<div class="min-h-full px-6 py-12 lg:px-8">
    <form class="flex flex-col" action="{{ route('patient.update', $patient->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="flex justify-between">
            <div class="flex flex-col w-full ml-2 justify-start">
                <!-- نام بیمار* -->
                <x-app.input.edit-inputs name="name" label="نام بیمار*" :value="old('name', $patient->name)" />

                <!-- نام خانوادگی بیمار* -->
                <x-app.input.edit-inputs name="family" label="نام خانوادگی بیمار*" :value="$patient->family" />

                <!-- نام پدر* -->
                <x-app.input.edit-inputs name="father_name" label="نام پدر*" :value="$patient->father_name" />

                {{-- national and passport codes --}}
                @if (! empty($patient->national_code))
                <div class="flex items-center gap-2">
                    <!-- تبعه خارجی -->
                    <div class="flex justify-between items-center w-full">
                        <x-app.input.edit-inputs type="checkbox" name="is_foreigner" label="تبعه خارجی" checked=""/>

                        <div id="show-passport-code" class="mt-4 hidden w-6/12">
                            <x-app.input.edit-inputs name="passport_code" label="شماره پاسپورت بیمار " :value="$patient->passport_code" />
                        </div>
                    </div>

                    <!-- کد ملی -->
                    <div id="show-national-code" class="w-full" >
                        <x-app.input.edit-inputs name="national_code" label="کد ملی بیمار " :value="$patient->national_code" />
                    </div>
                </div>
                @else
                <div class="flex items-center gap-2">
                    <!-- تبعه خارجی -->
                    <div class="flex justify-between items-center w-full">
                        <x-app.input.edit-inputs type="checkbox" name="is_foreigner" label="تبعه خارجی" checked="checked"/>

                        <div id="show-passport-code" class="mt-4 w-6/12">
                            <x-app.input.edit-inputs name="passport_code" label="شماره پاسپورت بیمار " :value="$patient->passport_code" />
                        </div>
                    </div>

                    <!-- کد ملی -->
                    <div id="show-national-code" class="w-full hidden" >
                        <x-app.input.edit-inputs name="national_code" label="کد ملی بیمار " :value="$patient->national_code" />
                    </div>
                </div>
                @endif

                <!-- شماره موبایل* -->
                <x-app.input.edit-inputs name="mobile" label="شماره موبایل*" :value="$patient->mobile"  type="number"/>

                <!-- شماره تلفن ثابت* -->
                <x-app.input.edit-inputs name="phone" label="شماره تلفن ثابت*" :value="$patient->phone"  type="number"/>
            </div>
            <div class="flex flex-col w-full mr-2 justify-between">
                <!-- address -->
                <x-app.input.edit-inputs name="address" label="آدرس*" :value="$patient->address" />

                <!-- birth_date -->
                {{-- TODO check why is not showing --}}
                <x-app.input.disabled-inputs name="birth_date" label="تاریخ تولد بیمار"  :value="empty($patient->birth_date) ? '-' : convertToJalali($patient->birth_date)" />

                <!-- جنسیت -->
                <x-app.input.edit-inputs name="gender" type="select" label="جنسیت*" >
                    {{ optionDetails('gender', $patient)}}
                </x-app.input.edit-inputs>

                <!-- تاهل -->
                <x-app.input.edit-inputs name="relation_status" type="select" label="تاهل*" >
                    {{ optionDetails('relation_status', $patient)}}
                </x-app.input.edit-inputs>

                <!-- انتخاب بیمه -->
                <x-app.input.edit-inputs name="insurance_id" type="select" label="انتخاب بیمه*" >
                    @if (count(App\Models\Insurance::all()) == 0)
                        <option value="0">موردی یافت نشد</option>
                    @else
                        @foreach (App\Models\Insurance::all() as $insurance)
                            <option value="{{$insurance->id}}" {{ $insurance->id == $patient->insurance_id ? 'selected' : ''}}>
                                {{ $insurance->title }}
                            </option>
                        @endforeach
                    @endif
                </x-app.input.edit-inputs>

                <!-- شماره بیمه -->
                <x-app.input.edit-inputs name="insurance_number" type="number" label="شماره بیمه" :value="empty($patient->insurance_number) ? '-' : $patient->insurance_number"  />
            </div>
        </div>
        {{-- عملیات ویرایش --}}
        <x-app.button.button-groups.edit-handlers :route="route('patient.show', $patient->id)" />
    </form>
</div>
<!-- Card end -->

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
