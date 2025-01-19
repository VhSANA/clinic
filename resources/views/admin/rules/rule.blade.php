@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <!-- عنوان انگلیسی -->
                    <x-app.input.disabled-inputs name="title" label="عنوان انگلیسی مقام" :value="$rule->title" />

                    <!-- عنوان فارسی -->
                    <x-app.input.disabled-inputs name="persian_title" label="عنوان فارسی مقام" :value="$rule->persian_title" />

                    <!-- عنوان انگلیسی -->
                    <x-app.input.disabled-inputs name="description" label="توضیحات مقام" type="textarea" :value="$rule->description" />

                    {{-- تاریخ تولید و ویرایش --}}
                    <x-app.input.show-create-update label="نقش کاربر" :model="$rule" />
                </div>
                {{-- عملیات ویرایش و حذف --}}
                <x-app.button.button-groups.show-handlers :delete="route('rule.destroy', $rule->id)" :edit="route('rule.edit', $rule->id)" />
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
