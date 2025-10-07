<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MypageEditViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_user_profile_and_address_initial_values_on_edit_page()
    {
        // 1. ユーザーを生成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        // 2. 住所を生成
        $address = Address::factory()->create([
            'user_id'       => $user->id,
            'postal_code'   => '100-0001',
            'address_line1' => '東京都千代田区千代田1-1',
            'address_line2' => 'テストマンション101',
            'is_default'    => true,
        ]);

        // 3. プロフィールを生成
        $profile = Profile::factory()->create([
            'user_id'     => $user->id,
            'display_name'=> 'テスト太郎',
            'image_url'   => 'https://example.com/profile.jpg',
            'address_id'  => $address->id,
        ]);

        // 4. ログイン状態を再現
        $this->actingAs($user);

        // 5. 編集ページにアクセス
        $response = $this->get(route('mypage.edit'));

        // 6. ステータス確認
        $response->assertStatus(200);

        // 7. 各項目の初期値が正しく表示されているか確認
        $response->assertSee('https://example.com/profile.jpg'); // プロフィール画像
        $response->assertSee('テスト太郎');                       // 表示名
        $response->assertSee('100-0001');                        // 郵便番号
        $response->assertSee('東京都千代田区千代田1-1');           // 住所1
        $response->assertSee('テストマンション101');               // 住所2
    }
}
