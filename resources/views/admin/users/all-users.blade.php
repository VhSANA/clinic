@extends('admin.layouts.master')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-center text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    نام کامل
                </th>
                <th scope="col" class="px-6 py-3">
                    نام کاربری
                </th>
                <th scope="col" class="px-6 py-3">
                    شماره موبایل
                </th>
                <th scope="col" class="px-6 py-3">
                    <div class="flex items-center justify-center">
                        سمت
                        {{-- TODO sortable --}}
                        <a href="#"><x-sort-icon /></a>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">
                    عملیات
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $user->full_name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $user->username }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $user->mobile }}
                    </td>
                    <td class="px-6 py-4">
                        @foreach ($user->rules as $rule)
                        {{ $rule->persian_title }}
                        @endforeach
                    </td>
                    <td class="px-6 py-4 text-center flex items-center justify-center">
                        <a href="{{route('users.show', $user->id)}}" class="font-medium ml-5 text-green-600 dark:text-blue-500 hover:underline">جزییات</a>
                        <a href="{{route('users.edit', $user->id)}}" class="font-medium mx-5 text-blue-600 dark:text-blue-500 hover:underline">ویرایش</a>
                        <form action="{{route('users.destroy', $user->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium mr-5 text-red-600 dark:text-blue-500 hover:underline">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
