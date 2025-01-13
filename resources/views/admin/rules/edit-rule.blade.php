@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <form action="{{ route('rule.update', $rule->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- عنوان انگلیسی -->
                        <x-app.input.edit-inputs name="title" label="عنوان انگلیسی مقام" :value="$rule->title" />

                        <!-- عنوان فارسی -->
                        <x-app.input.edit-inputs name="persian_title" label="عنوان فارسی مقام" :value="$rule->persian_title" />

                        <!-- عنوان انگلیسی -->
                        <x-app.input.edit-inputs name="description" label="توضیحات مقام" type="textarea" :value="$rule->description" />

                        <div class=" row flex items-center justify-center gap-2">
                            <!-- تاریخ ایجاد پرسنل -->
                            <x-app.input.disabled-inputs name="created_at" label="تاریخ ایجاد پرسنل" :value="$rule->created_at == null ? '-' : \Carbon\Carbon::create($rule->created_at)->toDayDateTimeString()" />

                            <!-- تاریخ ویرایش پرسنل -->
                            <x-app.input.disabled-inputs name="updated_at" label="تاریخ ویرایش پرسنل" :value="$rule->updated_at == null ? '-' : \Carbon\Carbon::create($rule->updated_at)->toDayDateTimeString()" />
                        </div>
                        <div class="mt-5 flex gap-2 px-2 justify-around items-center">
                            <x-app.button.cancel-btn :route="route('rule.show', $rule->id)">لغو ویرایش</x-app.cancel-btn>
                            <x-app.button.edit-btn type="button">ثبت ویرایش</x-app.edit-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Card end -->
</div>
@endsection
