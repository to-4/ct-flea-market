<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメント保存
     */
    public function store(StoreCommentRequest $request, int $item_id)
    {

        // バリデーション済みデータの取得
        $validated = $request->validated();

        Comment::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
            'body'    => $validated['body'],
        ]);

        return redirect()->route('items.show', $item_id)
            ->with('success', 'コメントを投稿しました');
    }
}
