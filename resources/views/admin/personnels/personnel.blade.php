@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">

    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            @if (Auth::user()->id == $personnel->user->id)
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <x-app.images.profile :model="$personnel">{{ $personnel->full_name }}</x-app.images.profile>

                    <div class=" row flex items-center justify-center gap-2">
                        <!-- نام کاربری -->
                        <x-app.input.disabled-inputs name="username" label="نام کاربری مرتبط" :value="$personnel->user->username" />

                        <!-- سمت پرسنل -->
                        @if (count($personnel->user->rules))
                            @foreach ($personnel->user->rules as $rule)
                            <x-app.input.disabled-inputs name="rule" label="سمت پرسنل" :value="$rule->persian_title" />
                            @endforeach
                        @else
                            <x-app.input.disabled-inputs name="rule" label="سمت کاربر" value="-" />
                        @endif
                    </div>
                    <div class=" row flex items-center justify-center gap-2">
                        <!-- شماره پرسنلی -->
                        <x-app.input.disabled-inputs name="personnel_code" label="شماره پرسنلی" :value="$personnel->personnel_code" />

                        <!-- شماره موبایل -->
                        <x-app.input.disabled-inputs name="mobile" label="شماره موبایل" :value="$personnel->user->mobile" />
                    </div>
                    <div class=" row flex items-center justify-center gap-2">
                        <!-- تاریخ ایجاد پرسنل -->
                        <x-app.input.disabled-inputs name="created_at" label="تاریخ ایجاد پرسنل" :value="$personnel->created_at == null ? '-' : \Carbon\Carbon::create($personnel->created_at)->toDayDateTimeString()" />

                        <!-- تاریخ ویرایش پرسنل -->
                        <x-app.input.disabled-inputs name="updated_at" label="تاریخ ویرایش پرسنل" :value="$personnel->updated_at == null ? '-' : \Carbon\Carbon::create($personnel->updated_at)->toDayDateTimeString()" />
                    </div>
                </div>
                <div class="flex gap-2 px-2 justify-around items-center">
                    <x-app.button.delete-btn :route="route('personnel.destroy', $personnel->id)">حذف پرسنل</x-app.delete-btn>
                    <x-app.button.edit-btn :route="route('personnel.edit', $personnel->id)">ویرایش پرسنل</x-app.edit-btn>
                </div>
            </div>
            @else
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                        src="https://img.freepik.com/premium-photo/3d-render-man-doctor-avatar-round-sticker-with-cartoon-character-face-personnel-id-thumbnail-modern_1181551-95.jpg?semt=ais_hybrid" alt="">
                    <div class="py-2">
                        <h2 class="font-bold text-2xl text-gray-800 dark:text-white mb-1">شما مجاز به مشاهده اطلاعات این کاربر نیستید!</h2>
                    </div>
                </div>
            </div>
            @endif
        </div>
    <!-- Card end -->
</div>
@endsection
