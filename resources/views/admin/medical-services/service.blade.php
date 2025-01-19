@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <!-- عنوان خدمت درمانی -->
                    <x-app.input.disabled-inputs name="name" label="عنوان خدمت درمانی" :value="$service->name" />

                    <!-- توضیحات -->
                    <x-app.input.disabled-inputs name="description" label="توضیحات خدمت درمانی" type="textarea" :value="$service->description" />

                    <!--نمایش در لیست -->
                    <x-app.input.disabled-inputs name="display_in_list" label="در لیست نمایش داده شود" type="checkbox" :checked="$service->display_in_list" />

                    {{-- تاریخ تولید و ویرایش --}}
                    <x-app.input.show-create-update label="خدمت" :model="$service" />
                    </div>
                    {{-- عملیات ویرایش و حذف --}}
                    <x-app.button.button-groups.show-handlers :delete="route('service.destroy', $service->id)" :edit="route('service.edit', $service->id)" />
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
