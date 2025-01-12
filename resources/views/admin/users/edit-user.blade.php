@extends('admin.layouts.master')

@section('content')
<div class="h-screen dark:bg-gray-700 bg-gray-200 pt-12">
    <!-- Card start -->
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            @if (Auth::user()->id == $user->id)
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="flex flex-col items-center justify-center">
                            <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                            src="{{ profileImageFunction($user) }}" alt="profile image of {{ $user->full_name }}">

                            {{-- profile image upload--}}
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image_url">تصویر پروفایل</label>
                            <x-file-input id="image_url" name="image_url" />
                            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                            </div>
                            <div class="py-2">
                                <div class="flex item-center justify-center">
                                    <x-text-input  class="block mt-1 w-full placeholder-gray-300 text-center mb-2" type="text" name="full_name" value="{{$user->full_name}}" />
                                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                </div>

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
                            <div class="ml-1">
                                <x-input-label for="username" class="text-start my-2" :value="__('نام کاربری')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$user->username}}" name="username"/>
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <div class="ml-1">
                                <x-input-label for="rules" class="text-start my-2" :value="__('سمت پرسنل')" />
                                <select name="rules" id="rules" required class="rounded-lg border-gray-300 w-full placeholder-gray-300 ">
                                    @if (count(App\Models\Rule::all()) == 0)
                                        <option value="0">موردی یافت نشد</option>
                                    @else
                                        @foreach (App\Models\Rule::all() as $rule)
                                            <option value="{{ $rule->id }}" {{ in_array($rule->id, $user->rules->pluck('id')->toArray()) ? 'selected' : ''}}>
                                                {{ $rule->persian_title }} - {{ $rule->title }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('rules')" class="mt-2" />
                            </div>
                        </div>
                        <div class=" row flex items-center justify-center">
                            <div class="ml-1">
                                <x-input-label for="password" class="text-start my-2" :value="__('رمزعبور')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="password" placeholder="درصورت عدم تغییر خالی بگذارید" name="password"/>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="ml-1">
                                <x-input-label for="password_confirmation" class="text-start my-2" :value="__('تکرار رمزعبور')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="password" placeholder="درصورت عدم تغییر خالی بگذارید" name="password_confirmation"/>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                        <div class=" row flex items-center justify-center">
                            <div class="ml-1">
                                <x-input-label for="national_code" class="text-start my-2" :value="__('کد ملی')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="number" value="{{$user->national_code}}"  name="national_code" />
                                <x-input-error :messages="$errors->get('national_code')" class="mt-2" />
                            </div>

                            <div class="ml-1">
                                <x-input-label for="mobile" class="text-start my-2" :value="__('شماره موبایل')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="number" value="{{$user->mobile}}" name="mobile" />
                                <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
                            </div>
                        </div>
                        <div class=" row flex items-center justify-center">
                            <div class="ml-1">
                                <x-input-label for="created_at" class="text-start my-2" :value="__('تاریج ایجاد شدن')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="text" value="{{\Carbon\Carbon::create($user->created_at)->toDayDateTimeString()}}" disabled />
                            </div>

                            <div class="ml-1">
                                <x-input-label for="updated_at" class="text-start my-2" :value="__('تاریج آخرین ویرایش')" />
                                <x-text-input  class="block mt-1 w-full placeholder-gray-300" type="text" value="{{$user->updated_at == null ? '-' : \Carbon\Carbon::create($user->updated_at)->toDayDateTimeString()}}" disabled />
                            </div>
                        </div>
                        <div class="mt-5 flex gap-2 px-2 justify-around items-center">
                            <a href="{{route('users.show', $user->id)}}"
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
