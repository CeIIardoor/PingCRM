<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CollaborateurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->numerify("+212 6 ## ## ## ##"),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'region' => $this->faker->region,
            'country' => 'FR',
            'postal_code' => $this->faker->postcode,
        ];
    }
}
