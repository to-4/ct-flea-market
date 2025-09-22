<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Comment model
 *
 * コメント情報
 *
 * Corresponding table: comments
 *
 * Properties:
 *
 * @property int $id
 *     主キーID（自動採番）
 *
 * @property UNSIGNED BIGINT $item_id
 *     商品ID
 *
 * @property UNSIGNED BIGINT $user_id
 *     ユーザーID
 *
 * @property TEXT $body
 *     コメント
 *
 * @property Carbon|null $created_at
 *     タスクが作成された日時（Laravelが自動で管理）
 *
 * @property Carbon|null $updated_at
 *     タスクが最後に更新された日時（Laravelが自動で管理）
 */
class Comment extends Model
{

    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
