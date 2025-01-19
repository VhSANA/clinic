<?php

namespace Database\Seeders;

use App\Models\Insurance;
use App\Models\MedicalServices;
use App\Models\Patient;
use App\Models\Personnel;
use App\Models\Room;
use App\Models\Rule;
use App\Models\User;
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
        User::factory()->create([
            'full_name' => 'سپهر برنا',
            'username' => 'sepehrbr',
            'national_code' => '1540541592',
            'mobile' => '09146947182',
            'user_title' => 'آقای',
            'gender' => 'male',
            'password' => Hash::make('password')
        ]);
        Personnel::factory()->create([
            'user_id' => User::find(1),
            'full_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->full_name;
            },
            'personnel_code' => random_int(100, 999),
            'image_url' => fake()->imageUrl()
        ]);
        User::factory(6)->create();
        Rule::factory(5)->create();
        Personnel::factory(5)->create();
        Insurance::factory(4)->create();
        Patient::factory(10)->create();
        MedicalServices::factory(10)->create();
        Room::factory(5)->create();
    }
}
