<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->word(),
            'is_active'  => true,
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
