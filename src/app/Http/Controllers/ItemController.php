<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{

    /**
     * 商品一覧表示
     */
    public function index()
    {
        // 全商品を取得（最新順）
        $items = Item::orderBy('created_at', 'desc')->get();

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
        ])->findOrFail($id);

        // ビューへ渡す
        return view('items.show', compact('item'));
    }

}
