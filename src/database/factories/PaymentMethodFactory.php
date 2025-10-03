<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->randomElement(['コンビニ支払い', 'カード払い']),
            'is_active'  => true,
            'sort_order' => $this->faker->numberBetween(1, 2),
        ];
    }
}
