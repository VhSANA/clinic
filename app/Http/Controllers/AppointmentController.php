<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentsStatus;
use App\Models\Calendar;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\MedicalServices;
use App\Models\Patient;
use App\Models\Personnel;
use App\Models\Room;
use App\Models\Schedule;
use App\Rules\TimeLimitValidation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Throwable;

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
                                        ->where('appointment_status_id', 1)
                                        ->exists();

            if ($prevent_new_visit_if_exists) {
                $registred_visit = Appointment::where('patient_id', $patient->id)
                                ->where('schedule_id', $schedule->id)
                                ->where('appointment_status_id', 1)
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
            $appointments = Appointment::with('patient', 'schedule.personnel','schedule.personnel.medicalservices', 'schedule.service', 'schedule.room', 'appointmentStatus', 'invoice', 'invoice.invoiceStatus')->get();
            // foreach ($appointments as $app) {
            //     dd($app->invoice->invoiceStatus);
            // }

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
        $request->validate([
            'appointment_id' => ['required', 'exists:appointments,id'],
            'discount' => ['nullable', 'min:4']
        ], [
            'appointment_id.required' => 'شیفتی انتخاب نشده است.',
            'appointment_id.exists' => 'تایم ویزیت انتخاب شده نامعتبر میباشد.',
            'discount.min' => 'کمترین مبلغ تخفیف حداقل باید 1000 تومان باشد. ',
        ]);

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
            //TODO check date. if it is happening in past days redirect back with error alert
            // if ($estimated_service_time < Carbon::now('Asia/Tehran')->toDateTimeString()) {
            //     Alert::error('خطا!','امکان پذیرش بیمار در زمان گذشته وجود ندارد.');

            //     // JSON response
            //     if (request()->expectsJson()) {
            //         return response()->json([
            //             'message' => 'امکان پذیرش بیمار در زمان گذشته وجود ندارد.',
            //             'status' => 'error'
            //         ], 500);
            //     }

            //     return back()->withInput($request->only('select_patient'));
            // }

            // TODO prevent from adding duplicated value
            // $duplication_exist = Appointment::where('estimated_service_time', $estimated_service_time)
            //                     ->where('patient_id', $patient->id)
            //                     ->where('schedule_id', $schedule->id)
            //                     ->exists();

            // if ($duplication_exist) {
            //     Alert::error('خطا!','مورد مشابه قبلا در سیستم ثبت شده است!');

            //     // JSON response
            //     if (request()->expectsJson()) {
            //         return response()->json([
            //             'message' => 'مورد مشابه قبلا در سیستم ثبت شده است!',
            //             'status' => 'error'
            //         ], 409);
            //     }

            //     return back();
            // }

            // TODO check, same patient cannot have another visit time except previous one is passed or paid
            // $prevent_new_visit_if_exists = Appointment::where('patient_id', $patient->id)
            //                             ->where('schedule_id', $schedule->id)
            //                             ->where('appointment_status_id', 1)
            //                             ->exists();

            // if ($prevent_new_visit_if_exists) {
            //     $registred_visit = Appointment::where('patient_id', $patient->id)
            //                     ->where('schedule_id', $schedule->id)
            //                     ->where('appointment_status_id', 1)
            //                     ->first();
            //     $registred_visit_time = Carbon::parse($registred_visit->estimated_service_time)->format('H:i');

            //     Alert::error('خطا!', "برای $patient->full_name در ساعت $registred_visit_time نوبت ثبت شده است.");

            //     // JSON response
            //     if (request()->expectsJson()) {
            //         return response()->json([
            //             'message' => 'قبلا نوبت ثبت شده است',
            //             'status' => 'error'
            //         ], 500);
            //     }

            //     return back()->withInput($request->only('select_patient'));
            // }

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
            Invoice::create([
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
                Alert::error('خطا!','عملیات غیرممکن!');

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
                Alert::error('خطا!','عملیات غیرممکن!');

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
        dd($request->all());
        try {
            // check date. if it is happening in past days redirect back with error alert
            if ($appointment->estimated_service_time < Carbon::now('Asia/Tehran')->toDateTimeString()) {
                Alert::error('خطا!','عملیات غیرممکن!');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }

            // cancel in appointments DB
            $appointment->update([
                'appointment_status_id' => AppointmentsStatus::find(3)->id,
                'canceled_user_id' => Auth::id(),
                'canceled_date' => Carbon::now('Asia/Tehran')->toDateTimeString(),
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => "نوبت رزرو شده با موفقیت حذف شد",
                    'status' => true
                ], 200);
            }

            Alert::success('عملیات موفق!','نوبت رزرو شده با موفقیت حذف شد.');

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
    * in this method we delete the whole row of appointment from DB.
    * this method doesnt updates cancel columns of DB
    */
    public function invoice(Request $request)
    {
        dd($request->all());
    }
}
