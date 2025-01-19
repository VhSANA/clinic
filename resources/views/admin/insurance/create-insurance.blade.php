@extends('admin.layouts.master')

@section('content')
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="w-full " action="{{ route('insurance.store') }}" method="POST">
            @csrf
            <!-- عنوان بیمه -->
            <x-app.input.all-inputs name="title" label="عنوان بیمه" placeholder="بیمه تامین اجتماعی" />

            <!-- توضیحات -->
            <x-app.input.all-inputs type="textarea" name="description" label="توضیحات" placeholder="توضیحی مختصر در مورد بیمه بنویسید (میتواند خالی باشد)" />

            {{-- عملیات ایجاد و لغو --}}
            <x-app.button.button-groups.create-handlers :cancel_route="route('insurance.index')" />
        </form>
    </div>
@endsection
