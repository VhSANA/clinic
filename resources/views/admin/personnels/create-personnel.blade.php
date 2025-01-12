@extends('admin.layouts.master')

@section('content')
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
      <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">افزودن کاربر جدید</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="w-full " action="{{ route('personnel.store') }}" method="POST">
        @csrf
        <!-- مقام پرسنل -->
        <div class="w-full">
            <x-input-label for="personnel" class="my-3" :value="__('انتخاب کاربر')" />
            <select name="personnel" id="personnel" required class="rounded-lg border-gray-300 w-full placeholder-gray-300 ">
                @php
                    $usersWithoutPersonnel = App\Models\User::doesntHave('personnel')->get();
                @endphp
                @if ($usersWithoutPersonnel->isEmpty())
                    <option value="0">موردی یافت نشد</option>
                @else
                    @foreach ($usersWithoutPersonnel as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->username }} - {{ $user->full_name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <x-input-error :messages="$errors->get('users')" class="mt-2" />
        </div>

        <!-- شماره پرسنلی -->
        <div>
            <x-input-label for="personnel_code" class="my-3" :value="__('کد پرسنلی کاربر')" />
            <x-text-input id="personnel_code" required class="block mt-1 w-full placeholder-gray-300" type="number" name="personnel_code" :value="old('personnel_code')"  autofocus autocomplete="personnel_code" placeholder="کد نظام پزشکی، پرستاری و ..." />
            <x-input-error :messages="$errors->get('personnel_code')" class="mt-2" />
        </div>

        <!-- شماره پرسنلی -->
        <div>
            <x-input-label for="image_url" class="my-3" :value="__('تصویر پروفایل')" />
            <x-file-input id="image_url" name="image_url" />
            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
        </div>

        <div class="flex justify-evenly mt-3">
            <button href="{{route('personnel.store')}}" class="rounded-full  bg-green-600 dark:bg-green-800 text-white dark:text-white antialiased font-bold hover:bg-green-800 dark:hover:bg-green-900 px-4 py-2 flex items-center justify-between transition w-4/12">
                افزودن
                <x-add-icon />
            </button>

            <a href="{{route('personnel.index')}}"
                class="rounded-full  bg-red-600 dark:bg-red-800 text-white dark:text-white antialiased font-bold hover:bg-red-800 dark:hover:bg-red-900 px-4 py-2 flex items-center justify-between transition w-4/12">
                لغو <x-cancel-icon />
            </a>
        </div>

      </form>
    </div>
  </div>
@endsection
