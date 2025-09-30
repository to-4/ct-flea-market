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
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * 購入画面を表示
     */
    public function index($item_id, Request $request)
    {

        $address_id = $request->query('address_id'); // GETパラメータから取得

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
    public function store(Request $request, $itemId)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $item = Item::findOrFail($itemId);

        // すでに購入済みならリダイレクト
        if ($item->purchase) {
            return redirect()->route('items.show', $item->id)
                             ->with('error', 'この商品はすでに購入されています。');
        }

        // 購入情報を登録
        $purchase = Purchase::create([
            'user_id'           => Auth::id(),
            'item_id'           => $item->id,
            'payment_method_id' => $request->payment_method_id,
            'status'            => 'pending', // 初期ステータス例
        ]);

        return redirect()->route('purchase.complete', $purchase->id)
                         ->with('success', '購入が完了しました！');
    }

    /**
     * 購入支払い先変更画面を表示
     */
    public function edit($item_id, Request $request)
    {

        $address_id = $request->query('address_id'); // GETパラメータから取得
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
}
