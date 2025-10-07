<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use App\Models\PaymentMethod;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PurchaseBuyTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function it_completes_purchase_when_user_clicks_buy_button(): void
    {
        // 1. 準備：ユーザー、住所、商品、支払い方法をを作成
        $user          = User::factory()         ->create();
        $address       = Address::factory()      ->create(['user_id' => $user->id]);
        $item          = Item::factory()         ->create(['name'    => 'テスト商品']);
        $paymentMethod = PaymentMethod::factory()->create();

        // 2. ログイン状態を再現
        $this->actingAs($user);

        // 3. 購入ページを開く（GET）
        $response = $this->get(route('purchase.index', ['item_id' => $item->id, 'address_id' => $address->id]));
        $response->assertStatus(200);
        $response->assertSee('購入する');

        // 4. フォームデータを定義（コントローラの引数に対応）
        $formData = [
            'user_id'             => $user->id,
            'item_id'             => $item->id,
            'payment_method_id'   => $paymentMethod->id,
            'address_id'          => $address->id,
        ];

        // 5. 購入ボタン押下（POST）
        $response = $this->post(route('purchase.store', $formData));

        // 6. ステータスと遷移先確認（リダイレクト想定）
        $response->assertStatus(302);
        $response->assertRedirect(route('mypage.index', ['page' => 'buy']));
        $response->assertSessionHas('success', '購入が完了しました！');

        // 7. データベース確認：購入情報が登録されている
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    #[Test]
    public function it_displays_sold_label_on_purchased_items(): void
    {
        // 1. ユーザー・住所・支払い方法・商品を作成
        $user          = User         ::factory()->create();
        $address       = Address      ::factory()->create(['user_id' => $user->id]);
        $paymentMethod = PaymentMethod::factory()->create();
        $item          = Item         ::factory()->create(['name' => 'テストバッグ']);

        // 2. 商品を購入状態にする（Purchase作成）
        Purchase::factory()->create([
            'user_id'             => $user->id,
            'item_id'             => $item->id,
            'payment_method_id'   => $paymentMethod->id,
            'shipping_address_id' => $address->id,
        ]);

        // 3. 一覧ページにアクセス
        $response = $this->actingAs($user)->get(route('index'));

        // 4. ステータス確認
        $response->assertStatus(200);

        // 5. 「sold」ラベルが表示されることを確認
        $response->assertSee('sold', false);

        // 6. 対象商品の名前も表示されていること
        $response->assertSee($item->name, false);
    }

    #[Test]
    public function it_adds_purchased_item_to_profile_list(): void
    {
        // 1. ユーザー、住所、商品、支払い方法を作成
        $user          = User         ::factory()
                                      ->has(profile::factory()) // 関連モデルを同時生成
                                      ->create();
        $address       = Address      ::factory()->create(['user_id' => $user->id]);
        $item          = Item         ::factory()->create(['name'    => 'トートバッグ']);
        $paymentMethod = PaymentMethod::factory()->create();

        // 2. ログイン状態にする
        $this->actingAs($user);

        // 3. 購入ページを開く
        $response = $this->get(route('purchase.index', [
            'item_id'    => $item->id,
            'address_id' => $address->id,
        ]));
        $response->assertStatus(200);
        $response->assertSee('購入する');

        // 4. 購入を実行
        $formData = [
            'user_id'           => $user->id,
            'item_id'           => $item->id,
            'payment_method_id' => $paymentMethod->id,
            'address_id'        => $address->id,
        ];

        $response = $this->post(route('purchase.store', $formData));

        // 5. リダイレクト確認（マイページへ）
        $response->assertRedirect(route('mypage.index', ['page' => 'buy']));

        // 6. 購入データが登録されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 7. プロフィール（マイページ）に遷移して購入済み商品が表示されているか確認
        $response = $this->get(route('mypage.index', ['page' => 'buy']));
        $response->assertStatus(200);
        $response->assertSee($item->name, false);
    }
}
