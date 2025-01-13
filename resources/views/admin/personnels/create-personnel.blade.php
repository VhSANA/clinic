@extends('admin.layouts.master')

@section('content')
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="w-full " action="{{ route('personnel.store') }}" method="POST">
            @csrf
            <!-- مقام پرسنل -->
            <x-app.input.all-inputs name="personnel" type="select" label="انتخاب کاربر" >
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
            </x-app.input.all-inputs>

            <!-- شماره پرسنلی -->
            <x-app.input.all-inputs name="personnel_code" type="number" label="کد پرسنلی کاربر" placeholder="1111" />

            <!-- شماره پرسنلی -->
            <x-app.input.all-inputs name="image_url" type="file" label="تصویر پروفایل" />


            <div class="flex justify-evenly mt-3">
                <x-app.button.add-btn >ایجاد</x-app.add-btn>
                <x-app.button.cancel-btn :route="route('personnel.index')">لغو</x-app.cancel-btn>
            </div>
        </form>
    </div>
@endsection
