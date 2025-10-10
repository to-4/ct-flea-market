<?php

namespace App\Services;

use App\Models\Item;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * 決済処理を開始して、リダイレクトURLを返す
     */
    public function createPaymentSession(Item $item, $method_id, $address_id): string
    {
        switch ($method_id) {
            case PaymentMethod::CODE_CARD:
                return $this->createCardSession($item, $address_id);

            case PaymentMethod::CODE_KONBINI:
                return $this->createKonbiniIntent($item);

            default:
                throw new \Exception('不明な支払い方法です');
        }
    }

    /**
     * カード支払い（Stripe Checkout）
     */
    protected function createCardSession(Item $item, $address_id): string
    {
        $user = Auth::user();
        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount'  => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => $user->email,
            'metadata' => [
                'user_id'    => $user->id,
                'item_id'    => $item->id,
                'method_id'  => PaymentMethod::CODE_CARD,
                'address_id' => $address_id,
            ],
            'success_url' => route('purchase.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('purchase.cancel', [], true),
        ]);

        return $session->url;
    }

    /**
     * コンビニ支払い（PaymentIntent）
     */
    protected function createKonbiniIntent(Item $item): string
    {
        $user = Auth::user();
        $intent = PaymentIntent::create([
            'amount'                 => $item->price,
            'currency'               => 'jpy',
            'payment_method_types'   => ['konbini'],
            'description'            => "Item: {$item->name}",
            'payment_method_options' => [
                'konbini' => [
                    'expires_after_days' => 3,
                ],
            ],
        ]);

        // confirm() を呼んで next_action を生成
        $confirmedIntent = $intent->confirm([
            'payment_method_data' => [
                'type' => 'konbini',
                'billing_details' => [
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
            ],
        ]);

        return $confirmedIntent->next_action->konbini_display_details->hosted_voucher_url ?? '/';
    }
}
