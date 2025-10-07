<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\PaymentMethod;


class PaymentMethodReflectTest extends DuskTestCase
{
    public function test_payment_method_reflects_on_screen(): void
    {
        $user    = User::         factory()->create();
        $item    = Item::         factory()->create(['name'    => 'トートバッグ']);
        $address = Address::      factory()->create(['user_id' => $user->id]);
        $method1 = PaymentMethod::factory()->create(['name'    => 'クレジットカード']);
        $method2 = PaymentMethod::factory()->create(['name'    => '銀行振込']);

        $this->browse(function (Browser $browser) use ($user, $item, $address, $method1, $method2) {

            $browser->loginAs($user)
                ->visit(route('purchase.index', [
                    'item_id'    => $item->id,
                    'address_id' => $address->id,
                ]))
                ->assertSee('支払い方法')
                ->select('#payment_method', $method2->id)
                ->pause(300) // JS反映待ち
                ->assertSee($method2->name)
                ->screenshot('payment_reflect');
        });
    }}
