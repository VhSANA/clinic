@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <form action="{{ route('personnel-service.update', $personnel_service->id) }}" method="post" >
                        @csrf
                        @method('PUT')
                        <!-- پرسنل ارائه دهنده -->
                        <x-app.input.edit-inputs type="select" name="personnel" label="پرسنل ارائه دهنده"  >
                            @foreach ($all_doctor_personnels as $doctor)
                                <option
                                    value="{{ $doctor->id }}"
                                    {{ $doctor->id == $personnel->id ? 'selected' : ''}}
                                >
                                    {{ $doctor->user->user_title }} {{ $doctor->full_name }} به کد پرسنلی: {{ $doctor->personnel_code }}
                                </option>
                            @endforeach
                        </x-app.input.edit-inputs>

                        <!-- خدمت قابل ارائه -->
                        <x-app.input.edit-inputs type="select" name="service" label="خدمت قابل ارائه"  >
                            @foreach ($all_services as $medical_service)
                                <option
                                    value="{{ $medical_service->id }}"
                                    {{ $medical_service->id == $service->id ? 'selected' : ''}}
                                >
                                    {{ $medical_service->name }}
                                </option>
                            @endforeach
                        </x-app.input.edit-inputs>

                        <!-- مدت زمان تقریبی خدمت -->
                        <x-app.input.edit-inputs name="estimated_service_time" label="مدت زمان تقریبی خدمت (فقط عدد وارد کنید)" :value="$personnel_service->estimated_service_time" />

                        <!-- هزینه ارائه خدمت -->
                        <x-app.input.edit-inputs name="service_price" label="هزینه ارائه خدمت (فقط عدد وارد کنید)" :value="$personnel_service->service_price" />

                        {{-- عملیات ویرایش --}}
                        <x-app.button.button-groups.edit-handlers :route="route('personnel-service.show', $personnel_service->id)" />
                    </form>
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
