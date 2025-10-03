<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'       => User::factory(),                    // ユーザーID
            'postal_code'   => $this->faker->postcode(),           // 郵便番号
            'address_line1' => $this->faker->prefecture() . "\n" . // 住所1
                               $this->faker->city() . "\n" .
                               $this->faker->streetAddress(),
            'address_line2' => $this->faker->secondaryAddress(),   // 住所2
            'is_default'    => $this->faker->boolean(50),          // デフォルトフラグ
        ];
    }
}
