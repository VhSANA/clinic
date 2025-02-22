<?php

namespace App\Http\Controllers;

use App\AppointmentStatus as AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\AppointmentsStatus;
use App\Models\Calendar;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\InvoiceStatus;
use App\Models\MedicalServices;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Personnel;
use App\Models\Room;
use App\Models\Schedule;
use App\Rules\TimeLimitValidation;
use Carbon\Carbon;
use Storage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Throwable;

use function PHPUnit\Framework\isNull;

class AppointmentController extends Controller
{
    /**
     * Display patient reception and personnel's shift
     */
    public function appointment(Request $request)
    {
        try {
            // get patients
            $chosen_patient = null;

            // search patient
            if ($request->has('search_patient')) {
                $keyword = $request->input('search_patient');

                $chosen_patient = Patient::query()->where('national_code', 'like', "%$keyword%")->first();
            } else
            // select patient
            if ($request->has('select_patient')) {
                $keyword = $request->input('select_patient');

                $chosen_patient = Patient::query()->find($keyword);
            }

            // Determine the start and end dates of the current week
            $currentDate = Carbon::now();
            if ($request->has('week')) {
                $currentDate = Carbon::parse($request['week']);
            }

            // Define the start of week as saturday and friday as end of week because of Persian calendar
            $startOfWeek = $currentDate->copy()->startOfWeek(6);
            $endOfWeek = $startOfWeek->copy()->addDays(6);

            // get calendar
            $calendars = Calendar::whereBetween('date', [$startOfWeek, $endOfWeek])->with('schedules')->get();

            // Extract schedules from the calendars
            $schedules = $calendars->flatMap(function ($calendar) {
                return $calendar->schedules->map(function ($schedule) use ($calendar) {
                    return [
                        'id' => $schedule->id,
                        'schedule_date' => jdate($calendar->date)->format('%A، %d %B %Y'),
                        'personnel' => Personnel::find($schedule->personnel_id),
                        'service' => MedicalServices::find($schedule->medical_service_id),
                        'from_date' => jdate($schedule->from_date)->format('H:i'),
                        'to_date' => jdate($schedule->to_date)->format('H:i'),
                        'room' => Room::find($schedule->room_id)->title,
                    ];
                });
            });

            // get appointmetns
            $appointments = Appointment::all();

            // Convert dates to Jalalian
            $startOfWeekJalali = jdate($startOfWeek);
            $endOfWeekJalali = jdate($endOfWeek);

            return view('admin.appointments.appointment.patient-reception', [
                'chosen_patient' => $chosen_patient,
                'patients' => Patient::latest()->paginate(10),
                'schedules' => $schedules,
                'startOfWeek' => $startOfWeekJalali,
                'endOfWeek' => $endOfWeekJalali,
                'currentDate' => $currentDate,
                'appointments' => $appointments,
            ]);
        } catch (Exception $e) {
            Alert::toast('خطایی در دریافت اطلاعات رخ داده است.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'خطایی رخ داده است.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }
    }

    /**
    * store validated data to DB
    */
    public function store(Request $request)
    {
        $identifier = $request['schedule_id'];

        // validation
        $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'service_id' => ['required', 'exists:medical_services,id'],
            'appointment_type' => ['required', 'in:normal,emergency,vip'],
            'personnel_id' => ['required', 'exists:personnels,id'],
            'introducer_id' => ['nullable', function ($attribute, $value, $fail) {
                if ($value != 0 && ! Personnel::where('id', $value)->exists()) {
                    $fail('پرسنل معرف انتخاب شده نامعتبر میباشد');
                }
            },
        ],
            'description' => ['nullable'],
            'time_' . $identifier => ['required', new TimeLimitValidation($request['schedule_id'])],
            'schedule_id' => ['required', 'exists:schedules,id']
        ], [
            'patient_id.required' => 'انتخاب بیمار الزامیست',
            'patient_id.exists' => 'بیمار انتخاب شده نامعتبر میباشد',
            'service_id.required' => 'انتخاب خدمت درمانی الزامیست',
            'service_id.exists' => 'خدمت درمانی انتخاب شده نامعتبر میباشد',
            'personnel_id.required' => 'انتخاب پرسنل الزامیست',
            'personnel_id.exists' => 'پرسنل انتخاب شده نامعتبر میباشد',
            'schedule_id.required' => 'انتخاب یک روز کاری الزامیست',
            'schedule_id.exists' => 'روز کاری انتخاب شده نامعتبر میباشد',
            'appointment_type.required' => 'انتخاب نوع نوبت الزامیست',
            'appointment_type.exists' => 'نوع نوبت انتخاب شده نامعتبر میباشد',
        ]);

        // set variables and get realated data
        $patient = Patient::find($request['patient_id']);
        $schedule = Schedule::find($request['schedule_id']);
        $calendar = Calendar::find($schedule->schedule_date_id);
        $estimated_service_time = Carbon::parse($calendar->date)->toDateString() . ' ' . $request['time_' . $identifier] . ':00';

        try {
            // check date. if it is happening in past days redirect back with error alert
            if ($estimated_service_time < Carbon::now('Asia/Tehran')->toDateTimeString()) {
                Alert::error('خطا!','امکان پذیرش بیمار در زمان گذشته وجود ندارد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'امکان پذیرش بیمار در زمان گذشته وجود ندارد.',
                        'status' => 'error'
                    ], 500);
                }

                return back()->withInput($request->only('select_patient'));
            }

            // prevent from adding duplicated value
            $duplication_exist = Appointment::where('estimated_service_time', $estimated_service_time)
                                ->where('patient_id', $patient->id)
                                ->where('schedule_id', $schedule->id)
                                ->exists();

            if ($duplication_exist) {
                Alert::error('خطا!','مورد مشابه قبلا در سیستم ثبت شده است!');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'مورد مشابه قبلا در سیستم ثبت شده است!',
                        'status' => 'error'
                    ], 409);
                }

                return back();
            }

            // check, same patient cannot have another visit time except previous one is passed or paid
            $prevent_new_visit_if_exists = Appointment::where('patient_id', $patient->id)
                                        ->where('schedule_id', $schedule->id)
                                        ->whereNot('appointment_status_id', 5)
                                        ->exists();

            if ($prevent_new_visit_if_exists) {
                $registred_visit = Appointment::where('patient_id', $patient->id)
                                ->where('schedule_id', $schedule->id)
                                ->first();
                $registred_visit_time = Carbon::parse($registred_visit->estimated_service_time)->format('H:i');

                Alert::error('خطا!', "برای $patient->full_name در ساعت $registred_visit_time نوبت ثبت شده است.");

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'قبلا نوبت ثبت شده است',
                        'status' => 'error'
                    ], 500);
                }

                return back()->withInput($request->only('select_patient'));
            }

            // save to db
            Appointment::create([
                'patient_id' => $request['patient_id'],
                'service_id' => $request['service_id'],
                'appointment_type' => $request['appointment_type'],
                'introducer_id' => $request['introducer_id'],
                'description' => $request['description'],
                'estimated_service_time' => $estimated_service_time,
                'schedule_id' => $request['schedule_id'],
                'user_id' => Auth::id(),
                'appointment_status_id' => AppointmentsStatus::find(1)->id
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => "زمان ویزیت با موفقیت ثبت شد",
                    'status' => true
                ], 200);
            }

            // success alert
            Alert::success('عملیات موفقیت آمیز!', "ساعت {$request['time_' . $identifier]} با موفقیت برای $patient->full_name ثبت شد.");

            return redirect(route('appointments.appointment'));
        } catch (Exception $e) {
            Alert::toast('خطایی رخ داده است.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'خطایی رخ داده است.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }
    }

    /**
    * show registered patients list and table
    */
    public function patientsList()
    {
        try {
            // default view of list
            $showList = true;

            // initial data values
            $appointments = Appointment::with('patient', 'schedule.personnel','schedule.personnel.medicalservices', 'schedule.service', 'schedule.room', 'appointmentStatus', 'invoice', 'invoice.invoiceStatus', 'invoice.payment')
            ->where('estimated_service_time', '>', Carbon::now('Asia/Tehran'))
            ->oldest('estimated_service_time')->get();

            // dd($appointments);
            return view('admin.appointments.appointments.registered-patients-list', [
                'showList' => $showList,
                'appointments' => $appointments,
            ]);
        } catch (Throwable $th) {
            throw $th;
            // Alert::error('خطا!', 'خطایی رخ داده است.');

            // // JSON response
            // if (request()->expectsJson()) {
            //     return response()->json([
            //         'message' => 'خطایی رخ داده است.',
            //         'status' => 'error'
            //     ], 500);
            // }

            // return back();
        }
    }

    /**
    * create and save invoice created for patient in the DB
    */
    public function patientsListStore(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'appointment_id' => ['required', 'exists:appointments,id'],
            'discount' => ['nullable', 'min:4']
        ], [
            'appointment_id.required' => 'شیفتی انتخاب نشده است.',
            'appointment_id.exists' => 'تایم ویزیت انتخاب شده نامعتبر میباشد.',
            'discount.min' => 'کمترین مبلغ تخفیف حداقل باید 1000 تومان باشد. ',
        ]);

        if ($validator->fails()) {
            response()->json([
                'message' => 'validation errors'
            ], 422);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with("discount_validation", $request['appointment_id']);
        }

        // get data
        $appointment = Appointment::with('patient', 'schedule.personnel', 'schedule.personnel.medicalservices', 'schedule.service', 'schedule.room', 'appointmentStatus')->find($request['appointment_id']);
        $currentPersianYearMonth = jdate()->format('%y%m');
        $latestInvoice = Invoice::latest()->first();

        // Determine the new invoice number
        if ($latestInvoice) {
            $latestInvoiceNumber = $latestInvoice->invoice_number;
            $latestPersianYearMonthValue = substr($latestInvoiceNumber, 0, 4);

            if ($latestPersianYearMonthValue == $currentPersianYearMonth) {
                // Check if the last digit(s) are 9, 99, 999, etc.
                $latestInvoiceValueWithoutYearMonth = substr($latestInvoiceNumber, 4);
                if (preg_match('/^9+$/', $latestInvoiceValueWithoutYearMonth)) {
                    $invoice_number = $currentPersianYearMonth . (intval($latestInvoiceValueWithoutYearMonth) + 1);
                } else {
                    $invoice_number = $latestInvoiceNumber + 1;
                }
            } else {
                // Reset the invoice number pattern if the month has changed
                $invoice_number = $currentPersianYearMonth . '1';
            }
        } else {
            // If no invoices exist, start with the new pattern
            $invoice_number = $currentPersianYearMonth . '1';
        }

        // if year doesnt start with 0 add 0 to it
        if (! str_starts_with($invoice_number, '0')) {
            $invoice_number = "0{$invoice_number}";
        }

        try {
            //TODO prevent from issuance of invoice if resereved time is < now
            if ($appointment->estimated_service_time < Carbon::now('Asia/Tehran')) {
                Alert::error('خطا!','امکان صدور فاکتور برای تاریخ گذشته وجود ندارد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز (صدور فاکتور در گذشته)',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // prevent from cancelation if it was canceled previously
            if (($appointment->appointmentStatus->status == AppointmentStatusEnum::CANCELLED->value) && (! empty($appointment->canceled_date))) {
                Alert::error('خطا!',"امکان صدور فاکتور برای نوبت کنسل شده وجود ندارد!");

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // get service price
            foreach ($appointment->schedule->personnel->medicalservices as $service) {
                if ($service->id == $appointment->schedule->service->id) {
                    $total_service_price = intval($service->pivot->service_price);
                }
            }

            // lessen service price if there is insurance value and discount
            $discount = intval($request['discount']) ?? 0;
            if (! empty($request['discount'])) {
                $to_pay_service_price = $total_service_price - ($discount /* + $insruance cost*/);
            }

            //TODO add cost column to insurance table and views and add values of it too  $service_price -= intval($appointment->patient->insurance->cost);

            // insert into to invoice and invoice_details tables
            $new_invoice = Invoice::create([
                'name' => $appointment->patient->name,
                'family' => $appointment->patient->family,
                'national_code' => $appointment->patient->national_code,
                'patient_mobile' => $appointment->patient->mobile,
                'appointment_date' => $appointment->created_at,
                'appointment_id' => $appointment->id,
                'insurance_name' => $appointment->patient->insurance->title,
                'insurance_number' => null,
                'insurance_id' => $appointment->patient->insurance->id,
                'total' => $total_service_price,
                'discount' => $discount,
                'insurance_cost' => 0,  // TODO add column to insurance table to get the value that is going to cost from total
                'total_to_pay' => $to_pay_service_price ?? $total_service_price,
                'paid_amount' => 0,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->full_name,
                'invoice_number' => $invoice_number,
                'estimated_service_time' => $appointment->estimated_service_time,
                'line_index' => 0,
                'is_foreigner' => $appointment->patient->is_foreigner,
                'passport_code' => $appointment->patient->passport_code ?? null,
                'payment_status_id' => InvoiceStatus::find(1)->id,
            ]);

            InvoiceDetails::create([
                'medical_service_name' => $appointment->schedule->service->name,
                'medical_service_price' => $total_service_price,
                'medical_service_id' => $appointment->schedule->service->id,
                'personnel_id' => $appointment->schedule->personnel->id,
                'personnel_name' => $appointment->schedule->personnel->full_name,
                'personnel_code' => $appointment->schedule->personnel->personnel_code,
                'room_id' => $appointment->schedule->room->id,
                'estimated_service_time' => $appointment->estimated_service_time,
                'invoice_id' => $new_invoice->id,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => "فاکتور با موفقیت صادر شد",
                    'status' => true
                ], 200);
            }

            // return back and open payment modal
            return back()->with('show_payment_and_invoice_modal', $request['appointment_id']);
        } catch (Throwable $th) {
            throw $th;
            // Alert::error('خطا!', 'خطایی رخ داده است.');

            // // JSON response
            // if (request()->expectsJson()) {
            //     return response()->json([
            //         'message' => 'خطایی رخ داده است.',
            //         'status' => 'error'
            //     ], 500);
            // }

            // return back();
        }
    }

    /**
     * in this method we delete the whole row of appointment from DB.
     * this method doesnt updates cancel columns of DB
     */
    public function deleteAppointment(Request $request, Appointment $appointment)
    {
        try {
            // check if invoice has been published for this patient
            $invoice = $appointment->invoice;
            if (! empty($invoice)) {
                Alert::error('خطا!','حذف نوبت ممکن نیست.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // check date. if it is happening in past days redirect back with error alert
            if ($appointment->estimated_service_time < Carbon::now('Asia/Tehran')->toDateTimeString()) {
                Alert::error('خطا!','حذف نوبت ممکن نیست.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // prevent from cancelation if it was canceled previously
            if (($appointment->appointmentStatus->status == AppointmentStatusEnum::CANCELLED->value) && (! empty($appointment->canceled_date))) {
                Alert::error('خطا!',"امکان حذف نوبت کنسل شده وجود ندارد!");

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // delete from DB
            $appointment->deleteOrFail();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => "نوبت رزرو شده با موفقیت حذف شد",
                    'status' => true
                ], 200);
            }

            Alert::success('عملیات موفق!','نوبت رزرو شده با موفقیت حذف شد.');

            return back();
        } catch (Throwable $th) {
            Alert::error('خطا!', 'خطایی رخ داده است.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'خطایی رخ داده است.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }
    }

    /**
     * this method cancels the appointment and updates canel columns of DB
     */
    public function cancelAppointment(Request $request, Appointment $appointment)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'cancel_description' => ['required', 'min:5']
        ], [
            'cancel_description.required' => 'فیلد نمیتواند خالی باشد.',
            'cancel_description.min' => 'حداقل 5 کاراکتر وارد نمایید.',
        ]);

        if ($validator->fails()) {
            response()->json([
                'message' => 'validation errors'
            ], 422);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with("cancel_validation", $appointment->id);
        }

        try {
            // check date. if it is happening in past days redirect back with error alert
            if ($appointment->estimated_service_time < Carbon::now('Asia/Tehran')->toDateTimeString()) {
                Alert::error('خطا!','کنسل کردن نوبت ممکن نیست.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // prevent from cancelation if it was canceled previously
            if (($appointment->appointmentStatus->status == AppointmentStatusEnum::CANCELLED->value) && (! empty($appointment->canceled_date))) {
                $canceled_date = jdate($appointment->canceled_date)->format('%d %B %Y');
                $canceled_time = jdate($appointment->canceled_date)->format('H:i');
                Alert::error('خطا!',"بیمار {$appointment->patient->full_name} قبلا در تاریخ $canceled_date در ساعت $canceled_time نوبت رزرو خود را کنسل کرده است!");

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // insert cancelation details into appointments DB
            $appointment->update([
                'appointment_status_id' => AppointmentsStatus::where('status', AppointmentStatusEnum::CANCELLED->value)->first()->id,
                'canceled_user_id' => Auth::id(),
                'canceled_date' => Carbon::now('Asia/Tehran')->toDateTimeString(),
                'cancel_description' => $request['cancel_description'],
            ]);

            // TODO عملیات عودت وجه و ثبت در دیتابیس در صورت پرداخت

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => "نوبت رزرو شده با موفقیت کنسل شد",
                    'status' => true
                ], 200);
            }

            Alert::success('عملیات موفق!','نوبت رزرو شده با موفقیت کنسل شد.');

            return back();
        } catch (Throwable $th) {
            throw $th;
            // Alert::error('خطا!', 'خطایی رخ داده است.');

            // // JSON response
            // if (request()->expectsJson()) {
            //     return response()->json([
            //         'message' => 'خطایی رخ داده است.',
            //         'status' => 'error'
            //     ], 500);
            // }

            // return back();
        }
    }

    /**
    * payment method which tracks transactions and adds to db
    */
    public function payments(Request $request, Invoice $invoice)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'invoice_id' => ['required', 'exists:bill_patient_invoice,id'],
            "price" => ['required', 'min:4'],
            "payment_method" => ['required', 'in:cash,card'],
            'payment_description' => ['nullable', 'min:5'],
        ], [
            'invoice_id.required' => 'مقدار نامتعبر',
            'invoice_id.exists' => 'فاکتور نامعتبر میباشد.',
            "price.required" => 'وارد نمودن مبلغ پرداختی اجباری میباشد. (به تومان وارد شود).',
            "price.min" => 'حداقل مبلغ پرداختی باید 1000 تومان باشد.',
            "payment_method.required" => 'یک روش پرداختی انتخاب نمایید.',
            "payment_method.in" => 'روش پرداختی انتخابی نامعتبر میباشد.',
            'payment_description.min' => 'توضیحات تراکنش حداقل باید شامل 5 کاراکتر باشد.',
        ]);

        if ($validator->fails()) {
            response()->json([
                'message' => 'validation errors'
            ], 422);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with("payment_validation", $invoice->id);
        }

        // get data
        $in_queue_appointment_status = AppointmentsStatus::where('status', AppointmentStatusEnum::IN_QUEUE->value)->first();

        try {
            // add validation for max payment
            if ($request['price'] > $invoice->total_to_pay) {
                Alert::error('خطا!','مبلغ پرداختی نمیتواند بیشتر از مبلغ قابل پرداخت باشد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with("payment_validation", $invoice->id);
            }

            // prevent from payment if reservation < now
            if ($invoice->estimated_service_time < Carbon::now('Asia/Tehran')) {
                Alert::error('خطا در پرداخت!','نوبت رزرو شده منقضی شده است.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // prevent from payment if reservation is canceld
            if (($invoice->appointment->appointmentStatus->status == AppointmentStatusEnum::CANCELLED->value) && (! empty($invoice->appointment->canceled_date))) {
                Alert::error('خطا در پرداخت!','نمیتوان نوبت کنسل شده را پرداخت کرد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // prevent if payment is completed
            if ($invoice->payment_status_id == 2) {
                Alert::error('خطا در پرداخت!',"صورتحساب بشماره {$invoice->invoice_number} بصورت کامل پرداخت شده است.");

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

        // insert into DBs
            // bill_payment table
            $payment = Payment::create([
                'amount' => $request['price'],
                'payment_type' => $request['payment_method'],
                'description' => $request['payment_description'],
                'invoice_id' => $invoice->id,
                'user_name' => Auth::user()->full_name,
                'user_id' => Auth::id(),
            ]);

            // latest amount to pay
            $latest_payment = intval($invoice->paid_amount) + intval($payment->amount);

            // bill_invoices table
            $invoice->update([
                'paid_amount' => $latest_payment,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->full_name,
                'payment_status_id' => ($invoice->total_to_pay == $latest_payment ? 2 : $invoice->payment_status_id),
            ]);

            $patient_exists = Invoice::whereHas('appointment.schedule', function ($query) use ($invoice) {
                $query->where('personnel_id', $invoice->appointment->schedule->personnel->id)
                      ->where('medical_service_id', $invoice->appointment->schedule->service->id)
                      ->where('room_id', $invoice->appointment->schedule->room->id);
            })
            ->where('payment_status_id', 2)
            ->whereDate('estimated_service_time', Carbon::parse($invoice->estimated_service_time)->toDateString())
            ->max('line_index');

            if ($invoice->payment_status_id == 2) {
                $newLineIndex = ($patient_exists != null) ? $patient_exists + 1 : 1;

                // updae invoice line_index
                $invoice->update([
                    'line_index' => $newLineIndex,
                ]);
            }


            // appointments table
            $invoice->appointment->update([
                'appointment_status_id' => ($invoice->total_to_pay == $latest_payment ? $in_queue_appointment_status->id : $invoice->appointment->appointment_status_id),
                'user_id' => Auth::id()
            ]);

            if ($invoice->total_to_pay != $latest_payment) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => "عملیات پرداخت موفقیت آمیز",
                        'status' => true
                    ], 200);
                }

                Alert::success('عملیات موفق!','پرداخت موفق.');

                return back();
            }

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => "عملیات پرداخت موفقیت آمیز",
                    'status' => true
                ], 200);
            }

            Alert::success('عملیات موفق!',"صورتحساب بشماره {$invoice->invoice_number} بصورت کامل پرداخت شد و بیمار در صف {$invoice->line_index} قرار گرفته است.");

            return back();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * method for printing the invoice
     */
    public function printInvoice(Request $request, Appointment $appointment)
    {
        $invoice = $appointment->invoice;

        return view('print', [
            'invoice' => $invoice
        ]);
    }
}
