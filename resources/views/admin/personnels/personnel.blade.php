@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">

    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            @if (Auth::user()->id == $personnel->user->id)
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto mt-4"
                            src="{{ profileImageFunction(Auth::user()) }}" alt="profile picture of {{$personnel->full_name}}">
                    <div class="py-2">
                        <h3 class="font-bold text-2xl text-gray-800 dark:text-white mb-1">{{ $personnel->full_name }}</h3>
                    </div>

                    <div class=" row flex items-center justify-center">
                        <!-- نام کاربری -->
                        <div class="ml-1">
                            <x-input-label for="username" class="text-start my-2" :value="__('نام کاربری')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$personnel->user->username}}"  />
                        </div>
                        <!-- سمت پرسنل -->
                        <div class="ml-1">
                            <x-input-label for="personnel_title" class="text-start my-2" :value="__('سمت پرسنل')" />
                            @if (count($personnel->user->rules))
                                @foreach ($personnel->user->rules as $rule)
                                    <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$rule->persian_title}}"  />
                                @endforeach
                            @else
                                <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="-"  />
                            @endif
                        </div>
                    </div>
                    <div class=" row flex items-center justify-center">
                        <!-- شماره پرسنلی -->
                        <div class="ml-1">
                            <x-input-label for="personnel_code" class="text-start my-2" :value="__('شماره پرسنلی')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="number" value="{{$personnel->personnel_code}}"  />
                        </div>

                        <!-- شماره موبایل -->
                        <div class="ml-1">
                            <x-input-label for="personnel_title" class="text-start my-2" :value="__('شماره موبایل')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$personnel->user->mobile}}"  />
                        </div>
                    </div>
                    <div class=" row flex items-center justify-center">
                        <!-- عنوان پرسنل -->
                        <div class="ml-1">
                            <x-input-label for="personnel_title" class="text-start my-2" :value="__('تاریج ایجاد شدن')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{\Carbon\Carbon::create($personnel->created_at)->toDayDateTimeString()}}"  />
                        </div>

                        <div class="ml-1">
                            <x-input-label for="personnel_title" class="text-start my-2" :value="__('تاریج آخرین ویرایش')" />
                            <x-text-input  disabled class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$personnel->updated_at == null ? '-' : \Carbon\Carbon::create($personnel->updated_at)->toDayDateTimeString()}}"  />
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 px-2 justify-around items-center">
                    <form action="{{ route('personnel.destroy', $personnel->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button
                        class="flex-1 rounded-full bg-red-600 dark:bg-red-800 text-white dark:text-white antialiased font-bold hover:bg-red-800 dark:hover:bg-red-900 px-4 py-2 flex items-center justify-between">
                            حذف پرسنل <x-delete-icon />
                        </button>
                    </form>
                    <a href="{{route('personnel.edit', $personnel->id)}}"
                        class="rounded-full border-2 border-gray-400 dark:border-gray-700 font-semibold text-black dark:text-white px-4 py-2 hover:bg-gray-900 hover:text-white transition flex justify-center items-center">
                        ویرایش پرسنل <x-edit-icon />
                    </a>
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
