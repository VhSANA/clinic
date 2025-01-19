<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Insurance>
 */
class InsuranceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('fa_IR');

        $insurance = ['ملت','ایران','تامین اجتماعی','نیروهای مسلح','خدمات درمانی',];
        $int = array_rand($insurance);

        return [
            'title' => "بیمه " . $insurance[$int],
            'description' => $faker->realText,
        ];
    }
}
