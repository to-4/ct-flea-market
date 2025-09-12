<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemCondition;

class ItemConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 指定4件
        $contents = [
            [
                'name' => '良好',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => '目立った傷や汚れなし',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'やや傷や汚れあり',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => '状態が悪い',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($contents as $content) {
            ItemCondition::create($content);
        }
    }
}
