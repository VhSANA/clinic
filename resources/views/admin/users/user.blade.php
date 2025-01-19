@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            @if (Auth::user()->id == $user->id)
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    {{-- profile image --}}
                    <x-app.images.profile :model="$user">{{ $user->full_name }}</x-app.images.profile>

                    <div class=" row flex items-center justify-center gap-2">
                        <!-- نام کاربری -->
                        <x-app.input.disabled-inputs name="username" label="نام کاربری" :value="$user->username" />

                        <!-- سمت -->
                        @if (count($user->rules))
                            @foreach ($user->rules as $rule)
                                <x-app.input.disabled-inputs name="rule" label="سمت کاربر" :value="$rule->persian_title" />
                            @endforeach
                        @else
                            <x-app.input.disabled-inputs name="rule" label="سمت کاربر" value="-" />
                        @endif
                    </div>
                    <div class=" row flex items-center justify-center gap-2">
                        <!-- کدملی -->
                        <x-app.input.disabled-inputs name="national_code" label="کد ملی کاربر" :value="$user->national_code" />

                        <!-- موبایل -->
                        <x-app.input.disabled-inputs name="mobile" label="شماره موبایل کاربر" :value="$user->mobile" />
                    </div>

                    {{-- تاریخ تولید و ویرایش --}}
                    <x-app.input.show-create-update label="کاربر" :model="$user" />
                </div>

                {{-- عملیات ویرایش و حذف --}}
                <x-app.button.button-groups.show-handlers :delete="route('users.destroy', $user->id)" :edit="route('users.edit', $user->id)" />
            </div>
            @else
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                        src="https://img.freepik.com/premium-photo/3d-render-man-doctor-avatar-round-sticker-with-cartoon-character-face-user-id-thumbnail-modern_1181551-95.jpg?semt=ais_hybrid" alt="">
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
