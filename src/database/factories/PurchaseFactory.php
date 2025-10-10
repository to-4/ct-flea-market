<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id'             => Item::factory(),
            'user_id'             => User::factory(),
            'payment_method_id'   => PaymentMethod::factory(),
            'shipping_address_id' => Address::factory(),
        ];
    }
}
