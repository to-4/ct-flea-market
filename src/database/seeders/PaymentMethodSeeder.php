<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 指定2件
        $contents = [
            [
                'id' => PaymentMethod::CODE_KONBINI,
                'name' => 'コンビニ支払い',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => PaymentMethod::CODE_CARD,
                'name' => 'カード支払い',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($contents as $content) {
            PaymentMethod::create($content);
        }
    }
}
