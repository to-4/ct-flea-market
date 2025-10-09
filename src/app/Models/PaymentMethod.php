<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PaymentMethod model
 *
 * 支払方法
 *
 * Corresponding table: payment_methods
 *
 * Properties:
 *
 * @property int $id
 *     主キーID（自動採番）
 *
 * @property VARCHAR(255) $name
 *     支払方法の名称
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
class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * 支払いID：コンビニ払い
     * ※1. シーディングや Stripe サービスでも使用
     * ※2. Stripe の payment_method_type でも、'konbini' なのでそれに倣う
     */
    public const CODE_KONBINI = 1;
    /**
     * 支払いID：カード払い
     * ※ シーディングや Stripe サービスでも使用
     */
    public const CODE_CARD    = 2;

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

    public function purchases()
    {
        return $this->HasMany(Purchase::class);
    }
}