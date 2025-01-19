@extends('admin.layouts.master')

@section('content')
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="w-full " action="{{ route('personnel-service.store') }}" method="POST">
            @csrf
            <!-- انتاخب پرسنل -->
            <x-app.input.all-inputs name="personnel" type="select" label="انتخاب پرسنل" >
                @foreach ($personnels as $personnel)
                    <option value="{{ $personnel->id }}" {{ old('personnel') == $personnel->id ? 'selected' : '' }}>
                            {{ $personnel->user->user_title }} {{ $personnel->full_name }} به کد پرسنلی: {{ $personnel->personnel_code }}
                    </option>
                @endforeach
            </x-app.input.all-inputs>

            <!-- انتاخب خدمت -->
            <x-app.input.all-inputs name="service" type="select" label="انتخاب خدمت درمانی" >
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" {{ old('service') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }} - {{ substrDescription($service) }}
                    </option>
                @endforeach
            </x-app.input.all-inputs>

            <!-- مدت زمان تقریبی ارائه خدمت -->
            <x-app.input.all-inputs name="estimated_service_time" type="number" label="مدت زمان تقریبی ارائه خدمت (فقط عدد وارد کنید)" placeholder="مقدار را برحسب دقیقه وارد کنید." />

            <!-- هزینه خدمت -->
            <x-app.input.all-inputs name="service_price" type="number" label="هزینه خدمت (فقط عدد وارد کنید)" placeholder="مقدار را برحسب تومان وارد کنید." />

            {{-- عملیات ایجاد و لغو --}}
            <x-app.button.button-groups.create-handlers :cancel_route="route('personnel-service.index')" />
        </form>
    </div>
@endsection
