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

                        {{-- تاریخ تولید و ویرایش --}}
                        <x-app.input.show-create-update label="بیمه" :model="$insurance" />

                        {{-- عملیات ویرایش --}}
                        <x-app.button.button-groups.edit-handlers :route="route('insurance.show', $insurance->id)" />
                    </form>
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
