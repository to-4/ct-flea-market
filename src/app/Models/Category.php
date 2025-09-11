<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
}

/**
 * Category model
 *
 * 商品カテゴリ情報
 *
 * Corresponding table: categories
 *
 * Properties:
 *
 * @property int $id
 *     主キーID（自動採番）
 *
 * @property VARCHAR(255) $name
 *     商品カテゴリ名
 *
 * @property INT $sort_order
 *     ソート順
 *
 * @property BOOL $is_active
 *     有効フラグ
 *     false の場合は、無効
 *
 * @property Carbon|null $created_at
 *     タスクが作成された日時（Laravelが自動で管理）
 *
 * @property Carbon|null $updated_at
 *     タスクが最後に更新された日時（Laravelが自動で管理）
 */
class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'category_item');
    }
}

