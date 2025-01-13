@extends('admin.layouts.all-table-master')

@section('content.add-new')
    <div>
        <a href="{{route('insurance.create')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">افزودن بیمه جدید</a>
    </div>
@endsection

@section('content')
    {{-- all insurance table --}}
    @if (count($insurances) == 0)
        <table class="w-full text-sm text-left rtl:text-center text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        عنوان بیمه
                    </th>
                    <th scope="col" class="px-6 py-3">
                        توضیحات بیمه
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
                        عنوان بیمه
                    </th>
                    <th scope="col" class="px-6 py-3">
                        توضیحات بیمه
                    </th>
                    <th scope="col" class="px-6 py-3">
                        عملیات
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($insurances as $insurance)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $insurance->title }}
                        </th>
                        @if (is_null($insurance->description))
                            <td class="px-6 py-4">-</td>
                        @else
                            <td class="px-6 py-4">
                                {{ strlen($insurance->description) > 50 ? substr($insurance->description, 0, 50) . " ..." : $insurance->description }}
                            </td>
                        @endif
                        <td class="px-6 py-4 text-center flex items-center justify-center">
                            <a href="{{route('insurance.show', $insurance->id)}}" class="font-medium ml-5 text-green-600 dark:text-blue-500 hover:underline">جزییات</a>
                            <a href="{{route('insurance.edit', $insurance->id)}}" class="font-medium mx-5 text-blue-600 dark:text-blue-500 hover:underline">ویرایش</a>
                            <form action="{{route('insurance.destroy', $insurance->id)}}" method="post">
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
    <x-pagination :model="$insurances" path="App\Models\Insurance">
        {{ $insurances->links() }}
    </x-pagination>
@endsection
