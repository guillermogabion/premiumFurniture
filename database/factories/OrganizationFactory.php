<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'orgName' => $this->faker->name(),
            'address' => $this->faker->address(),
            'website' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
