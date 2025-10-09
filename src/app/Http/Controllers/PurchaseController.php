<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * 購入画面を表示
     */
    public function index($item_id)
    {

        $address_id = request()->query('address_id'); // GETパラメータから取得

        // 対象の商品を取得（出品者・住所リレーションもロード）
        $item = Item::with(['user.profile.address'])->findOrFail($item_id);

        // 支払い方法一覧を取得
        $paymentMethods = PaymentMethod::all();

        // ログインユーザーのプロフィール・住所情報を取得し、ビューに渡す

        $authUser = Auth::user();
        $userProfile = null;
        $userAddress = null;

        if ($authUser) {
            $authUser->loadMissing('profile');
            $userProfile = $authUser->profile;

            if ($address_id === null || $address_id === '') {
                $userProfile?->loadMissing('address');
                $userAddress = $userProfile?->address;
            } else {
                $userAddress = $authUser->addresses()->findOrFail($address_id);
            }
        }

        return view('purchases.index', compact('item', 'paymentMethods', 'userProfile', 'userAddress'));
    }

    /**
     * 購入処理を実行
     */
    public function store(Request $request, PaymentService $paymentService)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $item = Item::findOrFail($request->input('item_id'));
        $addressId = $request->input('address_id');
        $methodId  = $request->input('payment_method_id');

        // すでに購入済みならリダイレクト
        if ($item->purchase) {
            return redirect()->route('items.show', $item->id)
                ->with('error', 'この商品はすでに購入されています。');
        }

        // Stripe へ決済セッション作成（リダイレクトURLを受け取る）
        $url = $paymentService->createPaymentSession($item, $methodId, $addressId);

        // 外部URL（外部ドメイン）にリダイレクト
        return redirect()->away($url);
    }

    /**
     * 購入支払い先変更画面を表示
     */
    public function edit($item_id)
    {

        $address_id = request()->query('address_id'); // GETパラメータから取得
        $address = Address::findOrFail($address_id);

        return view('purchases.edit', compact('address', 'item_id'));
    }

    /**
     * 購入支払い先変更処理を実行
     */
    public function store_address(StoreAddressRequest $request, $item_id)
    {
        $validated = $request->validated();

        $address = new Address();
        $address->user_id       = Auth::id();
        $address->postal_code   = $validated['postal_code'];
        $address->address_line1 = $validated['address_line1'];
        $address->address_line2 = $validated['address_line2'];
        $address->is_default    = false;
        $address->save();

        return redirect()->route('purchase.index', ['item_id' => $item_id, 'address_id' => $address->id])
            ->with('success', '住所が更新されました。');
    }

    public function success(Request $request)
    {

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('mypage.index')->with('error', 'セッション情報がありません。');
        }

        // Stripe APIキーを設定（最初に必須）
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $itemId    = $session->metadata->item_id;
        $userId    = $session->metadata->user_id;
        $methodId  = $session->metadata->method_id;
        $addressId = $session->metadata->address_id;

        Purchase::create([
            'user_id'             => $userId,
            'item_id'             => $itemId,
            'payment_method_id'   => $methodId,
            'shipping_address_id' => $addressId,
        ]);

        return redirect()->route('mypage.index', ['page' => 'buy'])
            ->with('success', '購入が完了しました！');
    }

    public function cancel(Request $request)
    {

        return redirect()->route('mypage.index')
            ->with('error', '決済がキャンセルされました。');
    }
}
