<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\Like;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LikeIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_only_liked_items_and_not_own_items(): void
    {
        // 1. ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. 自分が出品した商品
        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        // 3. 他人が出品した商品
        $otherItem = Item::factory()->create([
            'name' => '他人の商品',
        ]);

        // 4. 自分が「いいね」した商品
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $otherItem->id,
        ]);

        // 5. マイリストページにアクセス
        $response = $this->get(route('index', ['tab' => 'mylist']));

        // 6. 他人の商品（いいね済み）は表示される
        $response->assertSee($otherItem->name);

        // 7. 自分の商品は表示されない
        $response->assertDontSee($myItem->name);
    }

    #[Test]
    public function it_displays_sold_label_for_purchased_items_in_mylist(): void
    {
        // 1. ユーザーを作成 & ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. 他人が出品した商品
        $item = Item::factory()->create([
            'name' => 'テスト商品',
        ]);

        // 3. 自分が「いいね」する
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 4. 商品を購入済みにする
        $address = Address::factory()->create(['user_id' => $user->id]);
        Purchase::factory()->create([
            'item_id'             => $item->id,
            'user_id'             => $user->id,
            'payment_method_id'   => PaymentMethod::factory(),
            'shipping_address_id' => $address->id,
        ]);

        // 5. マイリストページにアクセス
        $response = $this->get(route('index', ['tab' => 'mylist']));

        // 6. 「Sold」が表示されることを確認
        $response->assertSee('Sold');
    }

    #[Test]
    public function it_displays_nothing_for_guest_in_mylist(): void
    {
        // 1. 未ログイン状態でマイリストにアクセス
        $response = $this->get(route('index', ['tab' => 'mylist']));

        // 2. ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 3. 「マイリストが空」であることを確認
        $response->assertSee('表示する商品がありません');
    }
}
