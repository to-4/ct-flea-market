<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\ItemCondition;
use App\Http\Requests\StoreItemRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{

    /**
     * 商品一覧表示
     */
    public function index()
    {

        $tab = request()->query('tab', 'recommend');
        $keyword = request()->query('keyword'); // 検索キーワード

        // 商品情報を取得
        // - 各商品の購入数も一緒に取得（Sold 表示用）
        // - 検索キーワードがある場合は、商品名 or 商品説明に部分一致するもの
        // - マイリストタブの場合は、ログインユーザのいいね商品のみ
        // - それ以外は全商品（ログイン中なら自分の商品は除外）
        // - 1ページ12件ずつ表示

        // 商品情報を取得(全件)
        // - 各商品の購入数も一緒に取得（Sold 表示用）
        $itemsQuery = Item::withCount('purchase');

        // 絞り込み１
        // - マイリストタブの場合
        //   - ログイン中：ユーザのいいね商品のみ
        //   - ログインしていない：0件とする
        // - マイリストタブ以外の場合、ログイン中なら自分の出店商品以外のみ
        if ($tab === 'mylist') {
            if (!Auth::check()) {
                $itemsQuery->whereRaw('0 = 1');
            }
            else {
                $itemsQuery->whereHas('likes', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            }
        } else {
            if (Auth::check()) {
                $itemsQuery->where('user_id', '<>', Auth::id());
            }
        }

        // 絞り込み２
        // 検索キーワードによる絞り込み
        if ($keyword) {
            $itemsQuery->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%");
                });
        }

        // 登録日の最新順（ページネーション付きで取得(12件ずつ)）
        $items = $itemsQuery->orderBy('created_at', 'desc')->paginate(12);

        // ビューに渡す
        return view('items.index', compact('items'));
    }

    /**
     * 商品詳細ページを表示
     */
    public function show($id)
    {
        // Item を関連モデルごと取得
        $item = Item::with([
            'categories',    // 中間テーブル category_item 経由
            'itemCondition', // 商品状態 (item_conditions テーブル)
            'comments.user', // コメントとユーザー情報
            'purchase',      // 購入情報
        ])->findOrFail($id);

        // ビューへ渡す
        return view('items.show', compact('item'));
    }

    /**
     * いいねの追加・削除
     */
    public function toggleLike($id)
    {
        $item = Item::findOrFail($id);

        // すでにいいねしているかチェック
        $like = $item->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            // 既にいいねしていたら削除
            $like->delete();
        } else {
            // まだなら追加
            // ※ $item->likes()->create([...]) を呼ぶと、$item->id が自動で item_id にセットされる（user_id だけ渡せば十分）
            $item->likes()->create([
                'user_id' => Auth::id(),
            ]);
        }

        return back();
    }

    /**
     * 出品画面を表示 (GET /sell)
     */
    public function sell()
    {
        $categories = Category::all();
        $conditions = ItemCondition::all();

        return view('items.sell', compact('categories', 'conditions'));
    }

    /**
     * 商品を登録 (POST /store)
     */
    public function store(StoreItemRequest $request)
    {

        // バリデーション済みデータの取得
        $validated = $request->validated();

        // 画像アップロード
        $imagePath = null;
        if ($request->hasFile('image_url')) {
            // 画像を storage/app/public/items フォルダに保存
            $path = $request->file('image_url')->store('items', 'public');
            $imagePath = Storage::url($path);  // 公開URLを生成("/storage/items/abc123.jpg")
        }

        // Item 登録
        $item = Item::create([
            'name'              => $validated['name'],
            'bland_name'        => $validated['bland_name'] ?? null,
            'description'       => $validated['description'] ?? null,
            'price'             => $validated['price'],
            'item_condition_id' => $validated['item_condition_id'],
            'image_url'         => $imagePath,
            'user_id'           => Auth::id(),
        ]);

        // カテゴリ紐付け
        if (!empty($validated['categories'])) {
            $item->categories()->sync($validated['categories']);
        }

        return redirect()->route('mypage.index', ['page' => 'sell'])->with('success', '商品を出品しました！');
    }

}
