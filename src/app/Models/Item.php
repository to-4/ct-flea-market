<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Item model
 *
 * 商品情報
 *
 * Corresponding table: items
 *
 * Properties:
 *
 * @property int $id
 *     主キーID（自動採番）
 *
 * @property VARCHAR(255) $name
 *     商品名
 *
 * @property INT $price
 *     料金
 *
 * @property VARCHAR(255)|null $bland_name
 *     メーカー名
 *
 * @property TEXT $description
 *     説明
 *
 * @property VARCHAR(255) $image_url
 *     画像パス
 *
 * @property UNSIGNED BIGINT $user_id
 *     ユーザーID
 *     出展ユーザー
 *
 * @property UNSIGNED BIGINT $item_condition_id
 *     状態ID
 *
 * @property Carbon|null $created_at
 *     タスクが作成された日時（Laravelが自動で管理）
 *
 * @property Carbon|null $updated_at
 *     タスクが最後に更新された日時（Laravelが自動で管理）
 */
class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'bland_name',
        'description',
        'image_url',
        'user_id',
        'item_condition_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function itemCondition()
    {
        return $this->belongsTo(ItemCondition::class);
    }
    public function purchase()
    {
        return $this->HasOne(Purchase::class);
    }
    public function comments()
    {
        return $this->HasMany(Comment::class);
    }
    public function categories()
    {
        return $this->HasMany(Category::class, 'category_item');
    }
}
