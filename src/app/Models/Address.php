<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Address model
 *
 * 住所情報
 *
 * Corresponding table: addresss
 *
 * Properties:
 *
 * @property int $id
 *     主キーID（自動採番）
 *
 * @property UNSIGNED BIGINT $user_id
 *     ユーザID
 *
 * @property VARCHAR(10) $postal_code
 *     郵便番号
 *
 * @property VARCHAR(255) $address_line1
 *     住所１
 *
 * @property VARCHAR(255)|null $address_line2
 *     住所２
 *
 * @property BOOL $is_default
 *     デフォルトフラグ
 *     true の場合、プロフィールの住所
 *
 * @property Carbon|null $created_at
 *     タスクが作成された日時（Laravelが自動で管理）
 *
 * @property Carbon|null $updated_at
 *     タスクが最後に更新された日時（Laravelが自動で管理）
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'postal_code',
        'address_line1',
        'address_line2',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'bool',
    ];

    // 親（1） → 子（0|1）
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // 親（1） → 子（多）
    public function purchases()
    {
        // 子側FKが規格外のため、第2引数で指定
        return $this->hasMany(Purchase::class, 'shipping_address_id');
    }
}
