@extends('admin.layouts.master')

@section('content')
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="w-full " action="{{ route('room.store') }}" method="POST">
            @csrf
            <!-- عنوان اتاق -->
            <x-app.input.all-inputs name="title" label="نام اتاق" placeholder="برای مثال: اتاق ویزیت" />

            <!-- ظرفیت پرسنل اتاق -->
            <x-app.input.all-inputs name="personnel_capacity" label="حداکثر تعداد پرسنل در اتاق" placeholder="2" type="number" />

            <!-- ظرفیت پرسنل اتاق -->
            <x-app.input.all-inputs name="patient_capacity" label="حداکثر تعداد بیمار در اتاق" placeholder="4" type="number" />

            {{-- عملیات ایجاد و لغو --}}
            <x-app.button.button-groups.create-handlers :cancel_route="route('room.index')" />
        </form>
    </div>
@endsection
