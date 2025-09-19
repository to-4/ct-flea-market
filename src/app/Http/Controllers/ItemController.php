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
}
