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
                </div>
                <div class="flex gap-2 px-2 justify-around items-center">
                    <x-app.button.delete-btn :route="route('rule.destroy', $rule->id)">حذف مقام</x-app.delete-btn>
                    <x-app.button.edit-btn :route="route('rule.edit', $rule->id)">ویرایش مقام</x-app.edit-btn>
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
