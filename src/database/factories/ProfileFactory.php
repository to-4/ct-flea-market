<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'      => User::factory(),        // ユーザーID
            'address_id'   => Address::factory(),     // 住所ID
            'display_name' => $this->faker->name(),   // プロフィール名
            'image_url'    => $this->faker->imageUrl( // 画像パス
                                                640,       // 画像の幅(px)
                                                480,       // 画像の高さ(px)
                                                'product', // 画像カテゴリ
                                                true),     // ランダムな画像を毎回取得
        ];
    }
}
