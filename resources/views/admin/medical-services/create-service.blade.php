@extends('admin.layouts.master')

@section('content')
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="w-full " action="{{ route('service.store') }}" method="POST">
            @csrf
            <!-- عنوان خدمات درمانی -->
            <x-app.input.all-inputs name="name" label="عنوان خدمات درمانی" placeholder="برای مثال: ویزیت" />

            <!-- توضیحات -->
            <x-app.input.all-inputs type="textarea" name="description" label="توضیحات" placeholder="توضیحی مختصر در مورد خدمت درمانی بنویسید (میتواند خالی باشد)" />

            <!-- نمایش در لیست -->
            <x-app.input.all-inputs type="checkbox" name="display_in_list" label="در لیست نمایش داده شود" checked="true" />

            <div class="flex justify-evenly mt-3">
                <x-app.button.add-btn >ایجاد</x-app.add-btn>
                <x-app.button.cancel-btn :route="route('service.index')">لغو</x-app.cancel-btn>
            </div>
        </form>
    </div>
@endsection
