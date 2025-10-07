<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseShippingAddressTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_registers_shipping_address_when_user_purchases_an_item()
    {
        // 1. ユーザー・住所・商品・支払い方法を作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name'    => 'テストバッグ',
            'price'   => 15000,
            'user_id' => $user->id,
        ]);

        // 2. ログイン状態を再現
        $this->actingAs($user);

        // 3. 配送先を登録
        $formData = [
            'postal_code'   => '100-0001',
            'address_line1' => '東京都千代田区千代田１－１',
            'address_line2' => 'マンション１０１',
        ];
        $response = $this->put(route('purchase.store_address', ['item_id' => $item->id]), $formData);

        // 4. リダイレクト確認（購入画面へ）
        $address = Address::latest()->first(); // ※ 3 で登録された address は 最初のレコード
        $response->assertRedirect(route('purchase.index', [
            'item_id'    => $item->id,
            'address_id' => $address->id,
        ]));

        // 5. 変更された配送先が表示されているか確認
        $response = $this->get(route('purchase.index', ['item_id' => $item->id, 'address_id' => 1]));
        $response->assertSee('100-0001', false);
        $response->assertSee('東京都千代田区千代田１－１');
        $response->assertSee('マンション１０１');
    }

    /** @test */
    public function it_registers_and_links_shipping_address_to_purchased_item()
    {
        // 1. ユーザー・商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name'    => 'テストバッグ',
            'price'   => 15000,
        ]);

        // 2. ログイン状態を再現
        $this->actingAs($user);

        // 3. 配送先住所を登録
        $formData = [
            'postal_code'   => '100-0001',
            'address_line1' => '東京都千代田区千代田１−１',
            'address_line2' => 'マンション１０１',
        ];

        // 4. PUT リクエストを実行し、リダイレクトを追跡
        $response = $this->followingRedirects()
            ->put(route('purchase.store_address', ['item_id' => $item->id]), $formData);

        // 5. DB に住所が保存されていることを確認
        $address = Address::latest()->first();
        $this->assertDatabaseHas('addresses', [
            'id'            => $address->id,
            'user_id'       => $user->id,
            'postal_code'   => '100-0001',
            'address_line1' => '東京都千代田区千代田１−１',
            'address_line2' => 'マンション１０１',
        ]);

        // 6. 商品を購入（shipping_address_id を含めてPOST）
        $paymeent = PaymentMethod::factory()->create();
        $purchaseData = [
            'item_id'           => $item->id,
            'address_id'        => $address->id,
            'payment_method_id' => $paymeent->id,
        ];
        $response = $this->post(route('purchase.store'), $purchaseData);

        // 7. purchases テーブルに購入履歴が登録されていることを確認
        $purchase = Purchase::latest()->first();

        $this->assertDatabaseHas('purchases', [
            'id'                  => $purchase->id,
            'user_id'             => $user->id,
            'item_id'             => $item->id,
            'shipping_address_id' => $address->id,
        ]);

        // 8. 関連性（purchase.shipping_address_id === address.id）を直接確認
        $this->assertEquals($address->id, $purchase->shipping_address_id, '購入レコードの配送先IDが正しい');
    }
}
