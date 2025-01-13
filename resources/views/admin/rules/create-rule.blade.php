@extends('admin.layouts.master')

@section('content')
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="w-full " action="{{ route('rule.store') }}" method="POST">
            @csrf
            <!-- عنوان انگلیسی -->
            <x-app.input.all-inputs name="title" label="عنوان انگلیسی" placeholder="doctor" />

            <!-- عنوان فارسی -->
            <x-app.input.all-inputs name="persian_title" label="عنوان فارسی" placeholder="دکتر" />

            <!-- توضیحات -->
            <x-app.input.all-inputs type="textarea" name="description" label="توضیحات" placeholder="توضیحی مختصر در مورد مقام بنویسید (میتواند خالی باشد)" />

            <div class="flex justify-evenly mt-3">
                <x-app.button.add-btn >ایجاد</x-app.add-btn>
                <x-app.button.cancel-btn :route="route('rule.index')">لغو</x-app.cancel-btn>
            </div>
        </form>
    </div>
@endsection
