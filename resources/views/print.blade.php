<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاکتور شماره {{ $invoice->invoice_number }}</title>
    <style>
        /* TODO add @font-family of vazir and iransans */
        body {
            width: 8cm;
            display: flex;
            flex-direction: column;
            justify-items: center;
        }
        h1 {
            text-align: center;
            font-weight: bold;
            font-size: larger;
        }
        .invoice-header-top {
            display: flex;
            justify-content: space-between;
            padding: 0 10px;
        }
        .invoice-header-top p{
            font-weight: bold
        }
        .invoice-header-top span{
            font-weight: normal
        }
        .invoice-header-bottom {
            display: flex;
            justify-content: space-between;
            padding: 0 10px
        }
        .invoice-header-bottom p{
            font-weight: bold
        }
        .invoice-header-bottom span{
            font-weight: normal
        }
        .solid-hr {
            border: none;
            height: 4px;
            background-color: black;
            margin: 1px 0;
        }
        .patient-name {
            display: flex;
            margin: 5px 0;
            padding: 0 10px;
            font-size: medium
        }
        .patient-name p {
            margin: 0;
            margin-right: 10px;
            font-weight: normal;
        }
        .patient-nationalcode {
            display: flex;
            margin: 5px 0;
            padding: 0 10px;
            font-size: medium
        }
        .patient-nationalcode p {
            margin: 0;
            margin-right: 10px;
            font-weight: normal;
        }
        .service-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .service-row div {
            background-color: black;
            font-weight: bold;
            color: white;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
            text-align: center;
        }
        .service-row h3 {
            font-weight: normal;
            font-size: medium;
            text-align: center;
            margin: 0;

        }
        .service-row p {
            font-weight: normal;
            font-size: medium;
            text-align: center;
            margin: 5px 0
        }
        .dashed-hr {
            border: none;
            border-top: 2px dashed black;
            margin: 12px 0;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #ccc;
            margin: 10px 0
        }
        thead {
            background-color: black;
            color: white;
            font-weight: bold;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ccc;
        }
        tbody td {
            font-weight: 300; /* Thin text */
        }
        tr:last-child td {
            border-bottom: none;
        }
        .payment-details {
            display: flex;
            justify-content: space-between;
            padding: 0 10px;
        }
        .payment-details p {
            font-weight: bold
        }
        .payment-details span {
            font-weight: normal
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .buttons #print {
            background-color: #28a745; /* Green */
            color: white;
        }
        .buttons #print:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        .buttons #print:active {
            background-color: #1e7e34;
            transform: scale(0.98);
        }

        .buttons #cancel {
            background-color: #dc3545; /* Red */
            color: white;
        }
        .buttons #cancel:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
        .buttons #cancel:active {
            background-color: #a71d2a;
            transform: scale(0.98);
        }

        @media print {
            table {
                width: 100%;
                border-collapse: separate !important;
                border-spacing: 0 !important;
                border-radius: 10px !important;
                overflow: hidden !important;
                border: 1px solid #000 !important;
            }
            thead {
                background-color: black !important;
                color: white !important;
                font-weight: bold !important;
            }
            th, td {
                padding: 10px !important;
                text-align: center !important;
                border-bottom: 1px solid black !important;
            }
            tbody td {
                font-weight: 300 !important; /* Thin text */
            }
            tr:last-child td {
                border-bottom: none !important;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .buttons {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <h1>مطب دکتر قدیر محمدی</h1>
    <div class="invoice-header-top">
        <p>تاریخ: <span>{{ jdate($invoice->created_at)->toDateString() }}</span></p>
        <p>شماره فاکتور: <span>{{ $invoice->invoice_number }}</span></p>
    </div>
    <hr class="solid-hr">
    <div class="invoice-header-bottom">
        <p>{{ jdate($invoice->created_at)->format('%A، H:i') }}</p>
        <p>کاربر: <span>{{ $invoice->user->full_name }}</span></p>
    </div>
    <h3 class="patient-name">نام و نام خانوادگی بیمار: <p>{{ $invoice->name }} {{ $invoice->family }}</p></h3>
    <h3 class="patient-nationalcode">{{ ! empty($invoice->national_code) ? 'کدملی بیمار:' : 'Passport No:' }} <p>{{ ! empty($invoice->national_code) ? $invoice->national_code : $invoice->passport_code }}</p></h3>
    <hr class="dashed-hr">
    <div class="service-row">
        <div>
            محل خدمت
        </div>
        @php
            foreach ($invoice->appointment->schedule->personnel->medicalservices as $service) {
                $service_time = $service->pivot->estimated_service_time;
            }
        @endphp
        <h3>اتاق <p>{{ $invoice->appointment->schedule->room->title }}</p>مدت درمان: <p>{{ $service_time }} دقیقه</p></h3>
        <div class="">نوبت: {{ $invoice->line_index != 0 ? "ردیف $invoice->line_index" : '-' }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>خدمات</th>
                <th>خدمت دهنده</th>
                <th>مبلغ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->payment as $index => $value)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $value->invoice->appointment->schedule->service->name }}</td>
                    <td>{{ $value->invoice->appointment->schedule->personnel->full_name }}</td>
                    <td>{{ number_format($value->amount) }} تومان</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="payment-details">
        <p>دریافتی: <span>{{ number_format($invoice->paid_amount) }} تومان</span></p>
        <p>تخفیف: <span>{{ $invoice->discount != 0 ? number_format($invoice->discount) . ' تومان' : '-' }}</span></p>
    </div>

    <div class="buttons">
        <button id="print" onclick="window.print()">چاپ</button>
        <button id="cancel" onclick="window.close()">لغو</button>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
