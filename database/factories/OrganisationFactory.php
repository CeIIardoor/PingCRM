<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrganisationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->numerify("+212 5 ## ## ## ##"),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'region' => $this->faker->region,
            'country' => 'FR',
            'postal_code' => $this->faker->postcode,
        ];
    }
}
