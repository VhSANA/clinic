@extends('admin.layouts.master')

@section('content')
    <!-- Card start -->
        <div class="min-h-full px-6 py-12 lg:px-8">
            <div class="flex flex-col">
                <div class="flex justify-between">
                    <div class="flex flex-col w-full ml-2 justify-start">
                        {{-- name --}}
                        <x-app.input.disabled-inputs name="name" label="نام و نام خانوادگی بیمار" :value="$patient->full_name" />

                        {{-- father --}}
                        <x-app.input.disabled-inputs name="father_name" label="نام پدر بیمار" :value="$patient->father_name" />

                        @if ($patient->is_foreigner)
                            {{-- passport code --}}
                            <x-app.input.disabled-inputs name="passport_code" label="شماره پاسپورت بیمار" :value="$patient->passport_code" />
                        @else
                            {{-- national code --}}
                            <x-app.input.disabled-inputs name="national_code" label="کد ملی بیمار" :value="$patient->national_code" />
                        @endif

                        {{-- mobile --}}
                        <x-app.input.disabled-inputs name="mobile" label="موبایل بیمار" :value="$patient->mobile" />

                        {{-- phone --}}
                        <x-app.input.disabled-inputs name="phone" label="شماره تلفن ثابت" :value="empty($patient->phone) ? '-' : $patient->phone" />
                    </div>

                    <div class="flex flex-col w-full ml-2 justify-between">
                        {{-- address --}}
                        <x-app.input.disabled-inputs name="address" label="آدرس بیمار" :value="$patient->address" />

                        {{-- gender --}}
                        <x-app.input.disabled-inputs name="gender" label="جنسیت بیمار" :value="$patient->gender == 'male' ? 'مرد' : 'زن'" />

                        {{-- relation_status --}}
                        <x-app.input.disabled-inputs name="relation_status" label="وضعیت تاهل بیمار" :value="$patient->relation_status == 'single' ? 'مجرد' : 'متاهل'" />

                        {{-- insurance --}}
                        <x-app.input.disabled-inputs name="insurance" label="بیمه ثبت شده برای بیمار" :value="$patient->insurance->title" />

                        {{-- insurance_number --}}
                        <x-app.input.disabled-inputs name="insurance_number" label="شماره بیمه بیمار" :value="empty($patient->insurance_number) ? '-' : $patient->insurance_number" />
                    </div>
                </div>
                <div class="flex flex-col justify-center">
                    {{-- تاریخ تولید و ویرایش --}}
                    <x-app.input.show-create-update class="row flex justify-center gap-2" label="بیمار" :model="$patient" />
                    {{-- عملیات ویرایش و حذف --}}
                    <x-app.button.button-groups.show-handlers class="flex justify-center gap-2 mt-7" :delete="route('patient.destroy', $patient->id)" :edit="route('patient.edit', $patient->id)" />
                </div>
            </div>
        </div>
    <!-- Card end -->
@endsection
