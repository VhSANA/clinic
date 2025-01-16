@extends('admin.layouts.all-table-master')

@section('content.add-new')
    <div>
        <a href="{{route('service.create')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">افزودن خدمات درمانی جدید</a>
    </div>
@endsection

@section('content')
    {{-- all services table --}}
    @if (count($services) == 0)
        <table class="w-full text-sm text-left rtl:text-center text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        عنوان خدمت
                    </th>
                    <th scope="col" class="px-6 py-3">
                        توضیحات
                    </th>
                    <th scope="col" class="px-6 py-3">
                        نمایش
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
                    عنوان خدمت
                </th>
                <th scope="col" class="px-6 py-3">
                    توضیحات
                </th>
                <th scope="col" class="px-6 py-3">
                    نمایش
                </th>
                <th scope="col" class="px-6 py-3">
                    عملیات
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $service->name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ substrDescription($service) }}
                    </td>
                    <td class="px-6 py-4">
                        @if ($service->display_in_list == true)
                            <p class="font-small text-green-600 dark:text-green-500">بله</p>
                        @else
                            <p class="font-small text-red-600 dark:text-red-500">خیر</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center flex items-center justify-center">
                        <a href="{{route('service.show', $service->id)}}" class="font-medium ml-5 text-green-600 dark:text-blue-500 hover:underline">جزییات</a>
                        <a href="{{route('service.edit', $service->id)}}" class="font-medium mx-5 text-blue-600 dark:text-blue-500 hover:underline">ویرایش</a>
                        <form action="{{route('service.destroy', $service->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium mr-5 text-red-600 dark:text-blue-500 hover:underline">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- pagination --}}
    <x-pagination :model="$services" path="App\Models\MedicalServices">
        {{ $services->links() }}
    </x-pagination>
@endsection
