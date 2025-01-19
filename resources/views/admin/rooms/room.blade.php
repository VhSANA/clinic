@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <!-- نام اتاق -->
                    <x-app.input.disabled-inputs name="title" label="نام اتاق" :value="$room->title" />

                    <!-- ظرفیت پرسنل اتاق -->
                    <x-app.input.disabled-inputs name="personnel_capacity" label="ظرفیت پرسنل اتاق" :value="$room->personnel_capacity" />

                    <!-- ظرفیت بیمار اتاق -->
                    <x-app.input.disabled-inputs name="patient_capacity" label="ظرفیت بیمار اتاق" :value="$room->patient_capacity" />

                    {{-- تاریخ تولید و ویرایش --}}
                    <x-app.input.show-create-update label="اتاق" :model="$room" />

                    {{-- عملیات حذف و ویرایش --}}
                    <x-app.button.button-groups.show-handlers :delete="route('room.destroy', $room->id)" :edit="route('room.edit', $room->id)" />
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
