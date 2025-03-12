<?php

namespace Database\Seeders;

use App\AppointmentQueueStatus;
use App\AppointmentStatus;
use App\Models\AppointmentsStatus;
use App\Models\Insurance;
use App\Models\InvoiceStatus;
use App\Models\MedicalServices;
use App\Models\Patient;
use App\Models\Personnel;
use App\Models\PersonnelUser;
use App\Models\Room;
use App\Models\Rule;
use App\Models\RuleUser;
use App\Models\User;
use App\PatientBillStatus;
use Database\Factories\InsuranceFactory;
use Database\Factories\MedicalServicesFactory;
use Database\Factories\PersonnelFactory;
use Database\Factories\RuleFactory;
use Database\Factories\UserFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'full_name' => 'سپهر برنا',
        //     'username' => 'sepehrbr',
        //     'national_code' => '1540541592',
        //     'mobile' => '09146947182',
        //     'user_title' => 'آقای',
        //     'gender' => 'male',
        //     'password' => Hash::make('password')
        // ]);
        // Personnel::factory()->create([
        //     'user_id' => User::find(1),
        //     'full_name' => function (array $attributes) {
        //         return User::find($attributes['user_id'])->full_name;
        //     },
        //     'personnel_code' => random_int(100, 999),
        //     'image_url' => fake()->imageUrl()
        // ]);
        // User::factory(6)->create();
        // Rule::factory(3)->create();
        // RuleUser::factory(5)->create();
        // PersonnelFactory::createWithUserRelation(5);
        // InsuranceFactory::createAll();
        // Patient::factory(10)->create();
        // MedicalServices::factory(5)->create();
        // MedicalServicesFactory::createWithRelations();
        // Room::factory(5)->create();

    // generate appointment_status values using enum
        // AppointmentsStatus::create(['status' => AppointmentStatus::INITIAL_REGISTER->value]);
        // AppointmentsStatus::create(['status' => AppointmentStatus::FINAL_REGISTER->value]);
        // AppointmentsStatus::create(['status' => AppointmentStatus::CANCELLED->value]);
        // AppointmentsStatus::create(['status' => AppointmentStatus::TRANSFORMED->value]);
        // AppointmentsStatus::create(['status' => AppointmentStatus::COMPLETED->value]);
        // AppointmentsStatus::create(['status' => AppointmentStatus::IN_QUEUE->value]);
        // AppointmentsStatus::create(['status' => AppointmentStatus::RETURN_FROM_QUEUE->value]);

    // generate bill_patient_status values using enum
        // InvoiceStatus::create(['status' => PatientBillStatus::ISSUED->value]);
        // InvoiceStatus::create(['status' => PatientBillStatus::PAID->value]);
        // InvoiceStatus::create(['status' => PatientBillStatus::RETURNED->value]);

    // generate appointment_queues_states values using enum
        // ::create(['status' => AppointmentQueueStatus::IN_QUEUE->value]);
        // ::create(['status' => AppointmentQueueStatus::RECIEVING_SERVICE->value]);

    }
}
