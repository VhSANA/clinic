@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <form action="{{ route('service.update', $service->id) }}" method="post" >
                        @csrf
                        @method('PUT')

                        <!-- عنوان خدمت درمانی -->
                        <x-app.input.edit-inputs name="name" label="عنوان خدمت درمانی" :value="$service->name" />

                        <!-- توضیحات -->
                        <x-app.input.edit-inputs name="description" label="توضیحات خدمت درمانی" type="textarea" :value="$service->description" />

                        <!--نمایش در لیست -->
                        <x-app.input.edit-inputs name="display_in_list" label="در لیست نمایش داده شود" type="checkbox" :checked="$service->display_in_list" />

                        {{-- تاریخ تولید و ویرایش --}}
                        <x-app.input.show-create-update label="خدمت" :model="$service" />

                        {{-- عملیات ویرایش --}}
                        <x-app.button.button-groups.edit-handlers :route="route('service.show', $service->id)" />
                    </form>
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
