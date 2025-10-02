<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Profile;
use App\Models\Address;
use App\Models\Item;

use App\Http\Requests\UpdateProfileRequest;

class MypageController extends Controller
{

    /**
     * マイページ画面の表示
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // ユーザーのプロフィールを取得（なければ作成）
        $user->loadMissing('profile');
        $profile = $user->profile;
        $page = request()->query('page', 'buy'); // デフォルト(第2引数)は購入一覧

        if ($page === 'sell') {
            // 出品した商品一覧（自分が出品者）
            // == ↓ 20251002 ↓ == //
            // $items = Item::where('user_id', $user->id)
            //              ->orderBy('created_at', 'desc')
            //              ->get();
            $items = Item::withCount('purchase')
                         ->where('user_id', $user->id)
                         ->orderBy('created_at', 'desc')
                         ->get();
            // == ↑ 20251002 ↑ == //
        } else {
            // 購入した商品一覧（Purchase 経由で取得）
            // == ↓ 20251002 ↓ == //
            $items = $user->purchases()
                          ->with('item') // Purchase モデルに item() リレーションがある前提
                          ->latest()
                          ->get() // ユーザー購入履歴（商品データ付き）
                          ->map(fn($purchase) => $purchase->item); // 商品データのみ抽出
            // == ↑ 20251002 ↑ == //
        }

        return view('mypages.index', compact('user', 'items', 'profile', 'page'));
    }

    /**
     * マイページ（プロフィール設定）表示
     */
    public function edit(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // ユーザーのプロフィールを取得（なければ作成）
        $user->loadMissing('profile');
        $profile = $user->profile;

        if (!$profile) {
            // 新規生成（DB未保存）、住所は紐付けず初期値のまま
            $profile = new Profile();
        }
        else {
            // 既存プロフィールのときは住所も取得
            $profile->loadMissing('address');
        }

        return view('mypages.edit', compact('profile'));
    }

    /**
     * プロフィール新規作成
     */
    public function store(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // バリデーション済みデータ
        $validated = $request->validated();

        // アップロード画像の保存（任意）
        $imageUrl = '';
        if ($request->file('image_url')) {
            $path = $request->file('image_url')->store('profiles', 'public');
            $imageUrl = \Illuminate\Support\Facades\Storage::url($path);
        }

        // 既存デフォルト住所を解除してから作成
        Address::where('user_id', $user->id)->where('is_default', true)->update(['is_default' => false]);
        $address = Address::create([
            'user_id'       => $user->id,
            'postal_code'   => $validated['postal_code']   ?? '',
            'address_line1' => $validated['address_line1'] ?? '',
            'address_line2' => $validated['address_line2'] ?? null,
            'is_default'    => true,
        ]);

        Profile::create([
            'user_id'      => $user->id,
            'display_name' => $validated['displayName'],
            'image_url'    => $imageUrl,
            'address_id'   => $address->id,
        ]);

        return redirect()->route('index');
    }

    /**
     * プロフィール更新
     */
    public function update(UpdateProfileRequest $request, int $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // バリデーション済みデータ
        $validated = $request->validated();

        $profile = Profile::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        // 画像更新（任意）
        if ($request->hasFile('image_url')) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->putFile('profiles', $request->file('image_url'));
            $profile->image_url = \Illuminate\Support\Facades\Storage::url($path);
        }

        $profile->display_name = $validated['displayName'];

        // 住所更新（なければ作成して紐付け）
        $postal    = $validated['postal_code']   ?? null;
        $addr1     = $validated['address_line1'] ?? null;
        $addr2     = $validated['address_line2'] ?? null;

        if ($profile->address_id) {
            $address = Address::find($profile->address_id);
            if ($address) {
                $address->postal_code   = $postal   ?? $address->postal_code;
                $address->address_line1 = $addr1    ?? $address->address_line1;
                $address->address_line2 = $addr2; // 明示的に上書き（null可）
                $address->is_default    = true;
                $address->save();
            }
        } else {
            Address::where('user_id', $user->id)->where('is_default', true)->update(['is_default' => false]);
            $newAddress = Address::create([
                'user_id'       => $user->id,
                'postal_code'   => $postal   ?? '',
                'address_line1' => $addr1    ?? '',
                'address_line2' => $addr2    ?? null,
                'is_default'    => true,
            ]);
            $profile->address_id = $newAddress->id;
        }

        $profile->save();

        return redirect()->route('mypage.index', ['page' => 'sell']);
    }
}
