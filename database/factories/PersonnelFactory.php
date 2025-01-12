<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
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
            'user_id' => User::factory(),
            'full_name' => function (array $attributes) {
                return User::find($attributes['user_id'])->full_name;
            },
            'personnel_code' => random_int(100, 999),
            'image_url' => fake()->imageUrl()
        ];
    }
}
