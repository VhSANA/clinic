@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            @if (Auth::user()->id == $personnel->user->id)
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <form action="{{ route('personnel.update', $personnel->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="flex flex-col items-center justify-center">
                            <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                            src="{{ profileImageFunction(Auth::user()) }}" alt="profile image of {{ $personnel->full_name }}">

                            {{-- profile image upload--}}
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image_url">تصویر پروفایل</label>
                            <x-file-input id="image_url" name="image_url" />
                            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                            </div>
                            <div class="mt-2">
                                <div class="flex item-center justify-center">
                                    <x-text-input  class="block mt-2 w-full placeholder-gray-300 text-center " type="text" name="full_name" value="{{$personnel->full_name}}" />
                                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <div class=" row flex items-center justify-center">
                            <div class="ml-1">
                                <x-input-label for="username" class="text-start my-2" :value="__('نام کاربری')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$personnel->user->username}}" name="username"/>
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            {{-- شماره پرسنلی --}}
                            <div class="ml-1">
                                <x-input-label for="personnel_code" class="text-start my-2" :value="__('شماره پرسنلی')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="number" value="{{$personnel->personnel_code}}"  name="personnel_code" />
                                <x-input-error :messages="$errors->get('personnel_code')" class="mt-2" />
                            </div>
                        </div>

                        <div class=" row flex items-center justify-center">
                            <div class="ml-1">
                                <x-input-label for="created_at" class="text-start my-2" :value="__('تاریج ایجاد شدن')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="text" value="{{\Carbon\Carbon::create($personnel->created_at)->toDayDateTimeString()}}" disabled />
                            </div>

                            <div class="ml-1">
                                <x-input-label for="updated_at" class="text-start my-2" :value="__('تاریج آخرین ویرایش')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$personnel->updated_at == null ? '-' : \Carbon\Carbon::create($personnel->updated_at)->toDayDateTimeString()}}" disabled />
                            </div>
                        </div>
                        <div class="mt-5 flex gap-2 px-2 justify-around items-center">
                            <a href="{{route('personnel.show', $personnel->id)}}"
                                class="rounded-full  bg-red-600 dark:bg-red-800 text-white dark:text-white antialiased font-bold hover:bg-red-800 dark:hover:bg-red-900 px-4 py-2 flex items-center justify-between transition">
                                لغو ویرایش <x-cancel-icon />
                            </a>
                            <button type="submit"
                                class="rounded-full border-2 border-gray-400 dark:border-gray-700 font-semibold text-black dark:text-white px-4 py-2 hover:bg-gray-900 hover:text-white transition flex justify-center items-center">
                                ثبت ویرایش  <x-edit-icon />
                            </button>
                        </div>
                    </form>
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
