<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Purchase model
 *
 * 購入情報
 *
 * Corresponding table: purchases
 *
 * Properties:
 *
 * @property int $id
 *     主キーID（自動採番）
 *
 * @property UNSIGNED BIGINT $user_id
 *     ユーザID
 *
 * @property UNSIGNED BIGINT $item_id
 *     商品ID
 *
 * @property UNSIGNED BIGINT $payment_method_id
 *     支払い方法ID
 *
 * @property UNSIGNED BIGINT $shipping_address_id
 *     送付先住所ID
 *
 * @property Carbon|null $created_at
 *     タスクが作成された日時（Laravelが自動で管理）
 *
 * @property Carbon|null $updated_at
 *     タスクが最後に更新された日時（Laravelが自動で管理）
 */
class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method_id',
        'shipping_address_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
