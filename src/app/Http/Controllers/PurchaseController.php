<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
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
    public function store_address(Request $request, $item_id)
    {
    //     $request->validate([
    //         'postal_code' => 'required|string|max:10',
    //         'prefecture'  => 'required|string|max:100',
    //         'city'        => 'required|string|max:100',
    //         'address'     => 'required|string|max:255',
    //         'building'    => 'nullable|string|max:255',
    //         'phone'       => 'nullable|string|max:15',
    //     ]);

    //     $address = Address::findOrFail($address_id);

    //    // バリデーション済みデータを使って住所を更新
    //    $address->update($request->only([
    //        'postal_code',
    //        'prefecture',
    //        'city',
    //        'address',
    //        'building',
    //        'phone',
    //    ]));

        $address = new Address();
        $address->user_id       = Auth::id();
        $address->postal_code   = $request->input('postal_code');
        $address->address_line1 = $request->input('address_line1');
        $address->address_line2 = $request->input('address_line2');
        $address->is_default    = false;
        $address->save();

        return redirect()->route('purchase.index', ['item_id' => $item_id, 'address_id' => $address->id])
                         ->with('success', '住所が更新されました。');
    }
}
