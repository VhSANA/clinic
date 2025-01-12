@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">

    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            @if (Auth::user()->id == $user->id)
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                            src="{{ profileImageFunction($user) }}" alt="profile picture of {{$user->full_name}}">
                    <div class="py-2">
                        <h3 class="font-bold text-2xl text-gray-800 dark:text-white mb-1">{{ $user->full_name }}</h3>
                        <div class="inline-flex text-gray-700 dark:text-gray-300 items-center">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-600 mr-1" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path class=""
                                    d="M5.64 16.36a9 9 0 1 1 12.72 0l-5.65 5.66a1 1 0 0 1-1.42 0l-5.65-5.66zm11.31-1.41a7 7 0 1 0-9.9 0L12 19.9l4.95-4.95zM12 14a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                            </svg>
                            تبریز
                            {{-- TODO add address to users table --}}
                        </div>
                    </div>

                    <div class=" row flex items-center justify-center">
                        <!-- عنوان پرسنل -->
                        <div class="ml-1">
                            <x-input-label for="username" class="text-start my-2" :value="__('نام کاربری')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$user->username}}"  />
                        </div>
                        <!-- عنوان پرسنل -->
                        <div class="ml-1">
                            <x-input-label for="rules" class="text-start my-2" :value="__('سمت پرسنل')" />
                            @if (count($user->rules))
                                @foreach ($user->rules as $rule)
                                    <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$rule->persian_title}}"  />
                                @endforeach
                            @else
                                <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="-"  />
                            @endif
                        </div>
                    </div>
                    <div class=" row flex items-center justify-center">
                        <!-- عنوان پرسنل -->
                        <div class="ml-1">
                            <x-input-label for="national_code" class="text-start my-2" :value="__('کد ملی')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="number" value="{{$user->national_code}}"  />
                        </div>

                        <!-- عنوان پرسنل -->
                        <div class="ml-1">
                            <x-input-label for="mobile" class="text-start my-2" :value="__('شماره موبایل')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$user->mobile}}"  />
                        </div>
                    </div>
                    <div class=" row flex items-center justify-center">
                        <!-- عنوان پرسنل -->
                        <div class="ml-1">
                            <x-input-label for="created_at" class="text-start my-2" :value="__('تاریج ایجاد شدن')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{\Carbon\Carbon::create($user->created_at)->toDayDateTimeString()}}"  />
                        </div>

                        <div class="ml-1">
                            <x-input-label for="updated_at" class="text-start my-2" :value="__('تاریج آخرین ویرایش')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$user->updated_at == null ? '-' : \Carbon\Carbon::create($user->updated_at)->toDayDateTimeString()}}"  />
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 px-2 justify-around items-center">
                    <form action="{{ route('users.destroy', $user->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <x-app.delete-btn >حذف کاربر</x-app.delete-btn>
                    </form>
                    <x-app.edit-btn :route="route('users.edit', $user->id)">ویرایش کاربر</x-app.edit-btn>
                </div>
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
