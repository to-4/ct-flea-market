<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MypageUserInfoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_user_profile_and_items_for_logged_in_user()
    {
        // 1. ユーザAを生成（プロフィール付き）
        $userA = User::factory()->create(['name' => 'ユーザA']);
        Profile::factory()->create([
            'user_id'      => $userA->id,
            'display_name' => 'ユーザーＡ',
            'image_url'    => 'https://example.com/a_profile.jpg',
        ]);

        // 2. ユーザAが商品を出品
        $itemA = Item::factory()->create([
            'user_id' => $userA->id,
            'name'    => 'Aの出品商品',
            'price'   => 10000,
        ]);

        // 3. ユーザBを生成（プロフィール付き）
        $userB = User::factory()->create(['name' => 'ユーザB']);
        Profile::factory()->create([
            'user_id'      => $userB->id,
            'display_name' => 'ユーザーＢ',
            'image_url'    => 'https://example.com/b_profile.jpg',
        ]);

        // 4. ユーザBが商品を出品
        $itemB = Item::factory()->create([
            'user_id' => $userB->id,
            'name'    => 'Bの出品商品',
            'price'   => 12000,
        ]);

        // 5. ユーザBがユーザAの商品を購入 ※ 支払い先や支払い方法は Factory で対応
        Purchase::factory()->create([
            'user_id' => $userB->id,
            'item_id' => $itemA->id,
        ]);

        // 6. Bでログイン
        $this->actingAs($userB);

        // 7. プロフィールページを開く
        $response = $this->get(route('mypage.index'));

        // 8. ステータス確認
        $response->assertStatus(200);

        // 9. プロフィール画像と名前の表示確認
        $response->assertSee('https://example.com/b_profile.jpg');
        $response->assertSee('ユーザーＢ');

        // 10. 出品商品一覧（?page=sell）
        $responseSell = $this->get(route('mypage.index', ['page' => 'sell']));
        $responseSell->assertSee('Bの出品商品');
        $responseSell->assertDontSee('Aの出品商品');

        // 11. 購入商品一覧（?page=buy）
        $responseBuy = $this->get(route('mypage.index', ['page' => 'buy']));
        $responseBuy->assertSee('Aの出品商品');
        $responseBuy->assertDontSee('Bの出品商品');
    }
}
