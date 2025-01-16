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

                        <div class=" row flex items-center justify-center gap-2">
                            <!-- تاریخ ایجاد خدمت -->
                            <x-app.input.disabled-inputs name="created_at" label="تاریخ ایجاد خدمت" :value="$service->created_at == null ? '-' : \Carbon\Carbon::create($service->created_at)->toDayDateTimeString()" />

                            <!-- تاریخ ویرایش خدمت -->
                            <x-app.input.disabled-inputs name="updated_at" label="تاریخ ویرایش خدمت" :value="$service->updated_at == null ? '-' : \Carbon\Carbon::create($service->updated_at)->toDayDateTimeString()" />
                        </div>
                        <div class="mt-5 flex gap-2 px-2 justify-around items-center">
                            <x-app.button.cancel-btn :route="route('service.show', $service->id)">لغو ویرایش</x-app.cancel-btn>
                            <x-app.button.edit-btn type="button">ثبت ویرایش</x-app.edit-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
