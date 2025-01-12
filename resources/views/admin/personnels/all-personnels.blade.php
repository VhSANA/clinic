@extends('admin.layouts.all-table-master')

@section('content.add-new')
    <div>
        <a href="{{route('personnel.create')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">افزودن پرسنل جدید</a>
    </div>
@endsection

@section('content')
    {{-- all personnels table --}}
    @if (count($personnels) == 0)
        <table class="w-full text-sm text-left rtl:text-center text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        نام کامل پرسنل
                    </th>
                    <th scope="col" class="px-6 py-3">
                        نام کاربری پرسنل
                    </th>
                    <th scope="col" class="px-6 py-3">
                        شماره موبایل پرسنل
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
                    نام کامل پرسنل
                </th>
                <th scope="col" class="px-6 py-3">
                    نام کاربری پرسنل
                </th>
                <th scope="col" class="px-6 py-3">
                    شماره موبایل پرسنل
                </th>
                <th scope="col" class="px-6 py-3">
                    کد پرسنلی
                </th>
                <th scope="col" class="px-6 py-3">
                    <div class="flex items-center justify-center">
                        سمت پرسنل
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
                @foreach ($personnels as $personnel)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="{{ is_null($personnel->image_url) ? 'https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg' : $personnel->image_url }}" alt="profile image of {{ $personnel->full_name }}">
                            <div class="ps-3">
                                <div class="text-base font-semibold">{{ $personnel->full_name }}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            {{ $personnel->user->username }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $personnel->user->mobile }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $personnel->personnel_code }}
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($personnel->user->rules as $rule)
                                {{ $rule->persian_title }}
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-center flex items-center justify-center">
                            <a href="{{route('personnel.show', $personnel->id)}}" class="font-medium ml-5 text-green-600 dark:text-blue-500 hover:underline">جزییات</a>
                            <a href="{{route('personnel.edit', $personnel->id)}}" class="font-medium mx-5 text-blue-600 dark:text-blue-500 hover:underline">ویرایش</a>
                            <form action="{{route('personnel.destroy', $personnel->id)}}" method="post">
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
    <x-pagination :model="$personnels" path="App\Models\Personnel">
        {{ $personnels->links() }}
    </x-pagination>
@endsection
