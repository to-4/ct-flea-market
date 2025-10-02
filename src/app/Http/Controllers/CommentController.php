<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメント保存
     */
    public function store(Request $request, int $item_id)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:500',
        ]);

        Comment::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
            'body'    => $validated['body'],
        ]);

        return redirect()->route('items.show', $item_id)
                         ->with('success', 'コメントを投稿しました');
    }
}
