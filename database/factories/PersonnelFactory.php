<?php

namespace Database\Factories;

use App\Models\Personnel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personnel>
 */
class PersonnelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'full_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->full_name;
            },
            'personnel_code' => random_int(100, 999),
            'image_url' => 'https://avatar.iran.liara.run/public/' . User::inRandomOrder()->first()->id
        ];
    }

    /**
     * Create multiple Personnel entries and add corresponding data to personnel_user table.
     *
     * @param int $count
     * @return void
     */
    public static function createWithUserRelation(int $count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $personnel = Personnel::factory()->create();

            DB::table('personnel_user')->insert([
                'user_id' => $personnel->user_id,
                'personnel_id' => $personnel->id,
            ]);
        }
    }
}
