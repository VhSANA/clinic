@extends('admin.layouts.all-table-master')

@section('content.add-new')
    <div>
        <a href="{{route('personnel-service.create')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">افزودن خدمت جدید</a>
    </div>
@endsection

@section('content')
    {{-- all personnel table --}}
    @if (count($personnels) == 0)
        <table class="w-full text-sm text-left rtl:text-center text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        نام پرسنل
                    </th>
                    <th scope="col" class="px-6 py-3">
                        خدمت درمانی
                    </th>
                    <th scope="col" class="px-6 py-3">
                        هزینه خدمت
                    </th>
                    <th scope="col" class="px-6 py-3">
                        مدت زمان تقریبی
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
                        نام پرسنل
                    </th>
                    <th scope="col" class="px-6 py-3">
                        خدمت درمانی
                    </th>
                    <th scope="col" class="px-6 py-3">
                        هزینه خدمت
                    </th>
                    <th scope="col" class="px-6 py-3">
                        مدت زمان تقریبی
                    </th>
                    <th scope="col" class="px-6 py-3">
                        عملیات
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($personnels as $personnel)
                    @foreach ($personnel->medicalservices as $service)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="flex items-center justify-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $personnel->full_name }}
                            </th>
                            <td class="px-6 py-4">
                                @if ($personnel->medicalservices->isEmpty())
                                    -
                                @else
                                    {{ $service->name }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($personnel->medicalservices->isEmpty())
                                    -
                                @else
                                    {{ number_format($service->pivot->service_price) }} تومان
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($personnel->medicalservices->isEmpty())
                                    -
                                @else
                                    {{ $service->pivot->estimated_service_time }} دقیقه
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center flex items-center justify-center">
                                <a href="{{route('personnel-service.show', $service->pivot->id)}}" class="font-medium ml-5 text-green-600 dark:text-blue-500 hover:underline">جزییات</a>
                                <a href="{{route('personnel-service.edit', $service->pivot->id)}}" class="font-medium mx-5 text-blue-600 dark:text-blue-500 hover:underline">ویرایش</a>
                                <form action="{{route('personnel-service.destroy', $service->pivot->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium mr-5 text-red-600 dark:text-blue-500 hover:underline">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- pagination --}}
    <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4 px-2 pb-2" aria-label="Table navigation">
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">نمایش <span class="font-semibold text-gray-900 dark:text-white">1 الی {{ DB::table('medical_services_personnel')->count() >= 10 ? '10' : DB::table('medical_services_personnel')->count() }}</span> از <span class="font-semibold text-gray-900 dark:text-white">{{ DB::table('medical_services_personnel')->count() }}
        {{ $personnels->links() }}
    </nav>
@endsection
