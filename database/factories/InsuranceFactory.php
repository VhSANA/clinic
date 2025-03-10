<?php

namespace Database\Factories;

use App\Models\Insurance;
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

        $insurance = ['آزاد','تامین اجتماعی','نیروهای مسلح','خدمات درمانی',];
        $int = array_rand($insurance);

        return [
            'title' => "بیمه " . $insurance[$int],
            'description' => $faker->realText,
        ];
    }

    /**
     * Create entries for each insurance type.
     *
     * @return void
     */
    public static function createAll()
    {
        $insurances = ['آزاد','تامین اجتماعی','نیروهای مسلح','خدمات درمانی', 'تکمیلی'];

        foreach ($insurances as $insurance) {
            Insurance::factory()->create([
                'title' => "بیمه " . $insurance,
                'description' => FakerFactory::create('fa_IR')->realText,
            ]);
        }
    }
}
