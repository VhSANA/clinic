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
            <x-app.add-btn >ایجاد</x-app.add-btn>
            <x-app.cancel-btn :route="route('personnel.index')">لغو</x-app.cancel-btn>
        </div>

      </form>
    </div>
  </div>
@endsection
