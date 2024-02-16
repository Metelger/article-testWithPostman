<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = FakerFactory::create('pt_BR');

        return [
            "name" => $faker->name(),
            "phone" => [
                "number" => $faker->cellphone(true, true),
                "regionCode" => $faker->areaCode(),
                "countryCode" => "55",
            ],
            "email" => $faker->safeEmail(),
            "document" => $faker->cpf(), // Ou numerify('###.###.###-##')
        ];
    }
}
