<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    use HasFactory;
}

/**
 * Like model
 *
 * いいね情報
 *
 * Corresponding table: likes
 *
 * Properties:
 *
 * @property int $id
 *     主キーID（自動採番）
 *
 * @property BIGINT UNSIGNED $item_id
 *     商品ID
 *
 * @property BIGINT UNSIGNED $user_id
 *     ユーザーID
 *
 * @property Carbon|null $created_at
 *     タスクが作成された日時（Laravelが自動で管理）
 *
 * @property Carbon|null $updated_at
 *     タスクが最後に更新された日時（Laravelが自動で管理）
 */
class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}