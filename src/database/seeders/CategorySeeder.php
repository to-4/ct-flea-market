<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 指定14件
        $contents = [
            [
                'name' => 'ファッション',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => '家電',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'インテリア',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'レディース',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'メンズ',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'コスメ',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => '本',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'ゲーム',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'スポーツ',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'キッチン',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'ハンドメイド',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'アクセサリー',
                'sort_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'おもちゃ',
                'sort_order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'ベビー・キッズ',
                'sort_order' => 14,
                'is_active' => true,
            ],
        ];

        foreach ($contents as $content) {
            Category::create($content);
        }

    }
}
