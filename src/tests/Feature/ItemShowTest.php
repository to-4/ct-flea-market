<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\ItemCondition;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_all_required_information_on_item_detail_page(): void
    {
        // 1. 準備：関連データを作成
        $user      = User::         factory()->create(['name' => '太郎']);
        $category  = Category::     factory()->create(['name' => 'バッグ']);
        $condition = ItemCondition::factory()->create(['name' => '新品']);
        $item      = Item::         factory()->create([
            'name'              => 'トートバッグ',
            'brand_name'        => 'COACH',
            'price'             => 12800,
            'description'       => '通勤にも使いやすいトートバッグです。',
            'image_url'         => 'https://example.com/item.jpg',
            'user_id'           => $user->id,
            'item_condition_id' => $condition->id,
        ]);

        // 中間テーブルを介して item と category を紐づける
        $item->categories()->attach($category->id);

        // いいね情報
        Like::factory()->count(3)->create(['item_id' => $item->id]);

        // コメント情報
        $commentUser = User::factory()->withProfile('花子')->create(); // コメントユーザ
        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $commentUser->id,
            'body' => 'とても可愛いですね！',
        ]);

        // 2. 商品詳細ページを開く
        $response = $this->get(route('items.show', ['id' => $item->id]));

        // 3. ステータス確認
        $response->assertStatus(200);

        // 4. 表示内容の確認（assertSeeTextはタグ除去して検証）
        $response->assertSee(' src="https://example.com/item.jpg"', false); // 商品画像

        $response->assertSeeText('トートバッグ'); // 商品名
        $response->assertSeeText('COACH');       // ブランド名
        $response->assertSeeText('12,800');      // 価格

        $response->assertSee    ('<span class="count">3</span>', false); // いいね数 (HTMLエスケープ)
        $response->assertSee    ('<span class="count">1</span>', false); // コメント数 (HTMLエスケープ)
        $response->assertSeeText('通勤にも使いやすいトートバッグです。');   // 商品説明

        $response->assertSeeText('バッグ'); // カテゴリ
        $response->assertSeeText('新品');   // 商品状態

        $response->assertSeeText('花子');               // コメントしたユーザー名
        $response->assertSeeText('とても可愛いですね！'); // コメント内容
    }

    #[Test]
    public function it_displays_multiple_categories_on_item_detail_page(): void
    {
        // 1. 準備：関連データを作成
        $user      = User::         factory()->create(['name' => '太郎']);
        $condition = ItemCondition::factory()->create(['name' => '新品']);

        // 商品作成
        $item = Item::factory()->create([
            'name'              => 'トートバッグ',
            'brand_name'        => 'COACH',
            'price'             => 12800,
            'description'       => '通勤にも使いやすいトートバッグです。',
            'image_url'         => 'https://example.com/item.jpg',
            'user_id'           => $user->id,
            'item_condition_id' => $condition->id,
        ]);

        // 2. 複数カテゴリ作成
        $categoryA = Category::factory()->create(['name' => 'レディース']);
        $categoryB = Category::factory()->create(['name' => 'バッグ']);
        $categoryC = Category::factory()->create(['name' => '新作']);

        // 3. 中間テーブルで紐づけ
        $item->categories()->attach([$categoryA->id, $categoryB->id, $categoryC->id]);

        // 4. 商品詳細ページを開く
        $response = $this->get(route('items.show', ['id' => $item->id]));

        // 5. ステータス確認
        $response->assertStatus(200);

        // 6. 複数カテゴリがすべて表示されていることを確認
        $response->assertSeeText('レディース');
        $response->assertSeeText('バッグ');
        $response->assertSeeText('新作');
    }
}
