@extends('admin.layouts.all-table-master')


@section('content.add-new')
<div>
    <a href="{{route('users.create')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">افزودن کاربر جدید</a>
</div>
@endsection

@section('content')
    {{-- all users table --}}
    @if (count($users) == 0)
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
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        موردی یافت نشد :(
                    </th>
                </tr>
            </tbody>
        </table>
    @else
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
                        <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{-- TODO you should get image url after relation created btw user and perosnnel --}}
                            <img class="w-10 h-10 rounded-full" src="{{ profileImageFunction($user) }}" alt="Jese image">
                            <div class="ps-3">
                                <div class="text-base font-semibold">{{ $user->full_name }}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            {{ $user->username }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->mobile }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($user->rules->isEmpty())
                                -
                            @else
                                @foreach ($user->rules as $rule)
                                    {{ $rule->persian_title }}
                                @endforeach
                            @endif
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
            @endif
        </tbody>
    </table>

    {{-- pagination --}}
    <x-pagination :model="$users" path="App\Models\User">
        {{ $users->links() }}
    </x-pagination>
@endsection
