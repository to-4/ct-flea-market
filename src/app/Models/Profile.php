<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Profile model
 *
 * プロフィール情報
 *
 * Corresponding table: profiles
 *
 * Properties:
 *
 * @property int $id
 *     主キーID（自動採番）
 *
 * @property BIGINT UNSIGNED $user_id
 *     ユーザーID
 *
 * @property VARCHAR(255) $display_name
 *     プロフィール名
 *
 * @property BIGINT UNSIGNED $address_id
 *     住所ID
 *
 * @property Carbon|null $created_at
 *     タスクが作成された日時（Laravelが自動で管理）
 *
 * @property Carbon|null $updated_at
 *     タスクが最後に更新された日時（Laravelが自動で管理）
 */
class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'address_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
