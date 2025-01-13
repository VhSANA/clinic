@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <form action="{{ route('insurance.update', $insurance->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <!-- عنوان بیمه -->
                        <x-app.input.edit-inputs name="title" label="عنوان بیمه" :value="$insurance->title" />

                        <!-- توضیحات -->
                        <x-app.input.edit-inputs type="textarea" name="description" label="توضیحات بیمه" :value="$insurance->description" />

                        <div class=" row flex items-center justify-center gap-2">
                            <!-- تاریخ ایجاد بیمه -->
                            <x-app.input.disabled-inputs name="created_at" label="تاریخ ایجاد بیمه" :value="$insurance->created_at == null ? '-' : \Carbon\Carbon::create($insurance->created_at)->toDayDateTimeString()" />

                            <!-- تاریخ ویرایش بیمه -->
                            <x-app.input.disabled-inputs name="updated_at" label="تاریخ ویرایش بیمه" :value="$insurance->updated_at == null ? '-' : \Carbon\Carbon::create($insurance->updated_at)->toDayDateTimeString()" />
                        </div>
                        <div class="mt-5 flex gap-2 px-2 justify-around items-center">
                            <x-app.button.cancel-btn :route="route('insurance.show', $insurance->id)">لغو ویرایش</x-app.cancel-btn>
                            <x-app.button.edit-btn type="button">ثبت ویرایش</x-app.edit-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
