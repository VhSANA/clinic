@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <!-- نام کامل پرسنل -->
                    <x-app.input.disabled-inputs name="personnel" label="پرسنل ارائه دهنده خدمت درمانی" :value="$personnel->user->user_title . ' ' . $personnel->full_name . ' به کد پرسنلی: ' .  $personnel->personnel_code" />

                    <!-- خدمت -->
                    <x-app.input.disabled-inputs type="textarea" name="service" label="خدمت درمانی (همراه با توضیحات مربوطه)" :value="$service->name . ' - ' .  $service->description" />

                    <!-- مدت زمان تقریبی خدمت -->
                    <x-app.input.disabled-inputs name="estimated_service_time" label="مدت زمان تقریبی خدمت" :value="$personnel_service->estimated_service_time . ' دقیقه'" />

                    <!-- هزینه ارائه خدمت -->
                    <x-app.input.disabled-inputs name="service_price" label="هزینه ارائه خدمت" :value="number_format($personnel_service->service_price) . ' تومان'" />

                    {{-- عملیات ویرایش و حذف --}}
                    <x-app.button.button-groups.show-handlers :delete="route('personnel-service.destroy', $personnel_service->id)" :edit="route('personnel-service.edit', $personnel_service->id)" />
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
