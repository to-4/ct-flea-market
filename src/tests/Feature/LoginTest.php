<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function it_displays_error_message_when_email_is_missing(): void
    {
        // 1. 登録フォームページにアクセス
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // 2. メールアドレスを空にして送信
        $formData = [
            'email' => '', // ← 未入力
            'password' => 'password123',
        ];
        $response = $this->post(route('login.post'), $formData);

        // 3. バリデーションエラーをセッションに持つか確認
        $response->assertSessionHasErrors(['email']);

        // 4. 実際のBlade上にメッセージが出ることを確認
        $this->followingRedirects()
            ->post(route('login.post'), $formData)
            ->assertSee('メールアドレスを入力してください');
    }

    #[Test]
    public function it_displays_error_message_when_password_is_missing(): void
    {
        // 1. 登録フォームページにアクセス
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // 2. メールアドレスを空にして送信
        $formData = [
            'email' => 'taro@example.com',
            'password' => '', // ← 未入力
        ];
        $response = $this->post(route('login.post'), $formData);

        // 3. バリデーションエラーをセッションに持つか確認
        $response->assertSessionHasErrors(['password']);

        // 4. 実際のBlade上にメッセージが出ることを確認
        $this->followingRedirects()
            ->post(route('login.post'), $formData)
            ->assertSee('パスワードを入力してください');
    }

    #[Test]
    public function it_displays_error_message_when_login_information_is_wrong(): void
    {
        // 1. 登録フォームページにアクセス
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // 2. 存在しないユーザー情報で送信
        $formData = [
            'email' => 'notfound@example.com',
            'password' => 'wrongpassword',
        ];
        $response = $this->post(route('login.post'), $formData);

        // 3. バリデーションエラーをセッションに持つか確認
        $response->assertSessionHasErrors(['email']);

        // 4. 実際のBlade上にメッセージが出ることを確認
        $this->followingRedirects()
            ->post(route('login.post'), $formData)
            ->assertSee('ログイン情報が登録されていません');
    }

    #[Test]
    public function it_logs_in_user_with_valid_credentials(): void
    {

        // 1. 会員登録済みユーザーをDBに作成
        $user = User::factory()->create([
            'email' => 'taro@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 2. 登録フォームページにアクセス
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // 3. 正しい認証情報でログイン
        $formData = [
            'email' => 'taro@example.com',
            'password' => 'password123',
        ];
        $response = $this->post(route('login.post'), $formData);

        // 4. 実際に認証状態になっていることを確認
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function it_logs_out_authenticated_user(): void
    {


        // 1. ユーザーを作成 & ログイン状態にする
        $user = User::factory()->create([
            'email' => 'taro@example.com',
            'password' => bcrypt('password123'),
        ]);
        $this->actingAs($user);

        // 2. ログアウト実行（POST /logout）
        $response = $this->post(route('logout'));

        // 3. ユーザーが未認証になっていることを確認
        $this->assertGuest();
    }
}
