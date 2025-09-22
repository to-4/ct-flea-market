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
    //
    /**
     * 購入画面を表示
     */
    public function index($item_id)
    {
        // 対象の商品を取得（出品者・住所リレーションもロード）
        $item = Item::with(['user.profile.address'])->findOrFail($item_id);

        // 支払い方法一覧を取得
        $paymentMethods = PaymentMethod::all();

        // ログインユーザーのプロフィール・住所情報を取得し、ビューに渡す
        $authUser = Auth::user();
        $userProfile = null;
        $userAddress = null;

        if ($authUser) {
            // 必要な関連のみ遅延ロード（未ロード時のみ）
            $authUser->loadMissing('profile');
            $userProfile = $authUser->profile;
            $userAddress = $userProfile?->address;
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
}
