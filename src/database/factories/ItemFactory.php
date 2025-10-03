<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ItemCondition;


class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'              => $this->faker->words(3, true),            // 商品名
            'price'             => $this->faker->numberBetween(500, 50000), // 料金
            'bland_name'        => $this->faker->company,                   // メーカー名
            'description'       => $this->faker->paragraph,                 // 説明
            'user_id'           => User::factory(),                         // ユーザーID（リレーション）
            'item_condition_id' => ItemCondition::factory(),                // 状態ID（リレーション）
            'image_url'         => $this->faker->imageUrl(640, 480, 'products', true), // 画像パス
        ];
    }
}
