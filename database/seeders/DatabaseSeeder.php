<?php

namespace Database\Seeders;

use App\Models\Personnel;
use App\Models\Rule;
use App\Models\User;
use Database\Factories\PersonnelFactory;
use Database\Factories\RuleFactory;
use Database\Factories\UserFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        // Rule::factory(5)->create();
        // Personnel::factory(10)->create();
    }
}
