<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_all_items_on_index_page(): void
    {
        // 1. テスト用の商品を3件作成
        $items = Item::factory()->count(3)->create();

        // 2. 商品一覧ページにアクセス
        $response = $this->get(route('index'));

        // 3. ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 4. Blade上に全商品の名前が表示されていることを確認
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    #[Test]
    public function it_displays_sold_label_for_purchased_items(): void
    {
        // 1. 出品者を作成
        $seller = User::factory()->create();

        // 2. 商品を作成
        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 3. 購入者を作成
        $buyer = User::factory()->create();

        // 4. 購入情報を作成（購入済み状態にする）
        //    ※ payment_method_id, shipping_address_id は PurchaseFactory で自動セット
        Purchase::factory()->create([
            'item_id' => $item->id,
            'user_id' => $buyer->id,
        ]);

        // 5. 商品一覧ページにアクセス
        $response = $this->get(route('index'));

        // 6. 「Sold」ラベルが表示されることを確認
        $response->assertSee('Sold');
    }

    #[Test]
    public function it_does_not_display_items_created_by_logged_in_user(): void
    {
        // 1. ユーザーを2人作成
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // 2. ユーザーAが出品した商品
        $itemByA = Item::factory()->create([
            'user_id' => $userA->id,
            'name'    => 'ユーザーAの商品',
        ]);

        // 3. ユーザーBが出品した商品
        $itemByB = Item::factory()->create([
            'user_id' => $userB->id,
            'name'    => 'ユーザーBの商品',
        ]);

        // 4. ユーザーAでログイン (認証状態にする)
        $this->actingAs($userA);

        // 5. 商品一覧ページにアクセス
        $response = $this->get(route('index'));

        // 6. ユーザーAの商品は表示されない
        $response->assertDontSee($itemByA->name);

        // 7. ユーザーBの商品は表示される
        $response->assertSee($itemByB->name);
    }
}
