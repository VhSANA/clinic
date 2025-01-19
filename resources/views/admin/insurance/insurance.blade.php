@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <!-- نام بیمه -->
                    <x-app.input.disabled-inputs name="title" label="نام بیمه" :value="$insurance->title" />

                    <!-- توضیحات -->
                    <x-app.input.disabled-inputs name="description" label="توضیحات بیمه" type="textarea" :value="$insurance->description" />

                    {{-- تاریخ تولید و ویرایش --}}
                    <x-app.input.show-create-update label="بیمه" :model="$insurance" />
                </div>
                
                {{-- عملیات ویرایش و حذف --}}
                <x-app.button.button-groups.show-handlers :delete="route('insurance.destroy', $insurance->id)" :edit="route('insurance.edit', $insurance->id)" />
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
