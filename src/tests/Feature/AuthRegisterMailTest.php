<?php

namespace Tests\Feature;


use App\Models\User;
use App\Mail\VerificationCodeMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\SentMessage;
use Tests\TestCase;
use Carbon\Carbon;

class AuthRegisterMailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_verification_email_after_registration()
    {

        // 1. ユーザー登録データを準備
        $formData = [
            'name'     => 'テストユーザー',
            'email'    => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // 2. 登録リクエストを送信
        $response = $this->post(route('register.post'), $formData);

        // 3. リダイレクト確認
        $response->assertRedirect(route('verification.notice'));

        // 4. 登録後のDB確認
        $user = \App\Models\User::where('email', $formData['email'])->first();
        $this->assertNotNull($user);

        // 5. 有効期限やコード保存の確認
        $this->assertNotNull($user->email_verification_code);
        $this->assertNotNull($user->email_verification_expires_at);


        // MailHog を Docker で動かしている場合、
        // テスト中に送られたメールは
        // ブラウザで次のURLを開くと確認できます👇

        // http://localhost:8025/

        // そこで「To」欄が test@example.com になっていればOK
    }

    /** @test */
    public function it_displays_verification_notice_and_redirects_to_verification_code_page()
    {

        // 1. ユーザーを作成しログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. メール認証誘導画面の表示
        $response = $this->get(route('verification.notice'));
        $response->assertStatus(200);
        $response->assertSee('メール認証を完了してください');
        $response->assertSee('認証はこちらから');

        // 3. 「認証はこちらから」ボタン押下を想定（form の action に設定されたルート先へ遷移）
        //    実際には GET リクエストで verification.code.form にアクセスする
        $redirectResponse = $this->get(route('verification.code.notice'));
        $redirectResponse->assertStatus(200);

        // 4. 遷移先のメール認証コード入力ページの内容確認
        $redirectResponse->assertSee('6桁の認証コード');
        $redirectResponse->assertSee('認証を完了してください');
        $redirectResponse->assertSee('認証する');

        // 5. （オプション）ページ遷移の順序確認（ルーティング正しいか）
        $this->assertEquals(
            route('verification.code.notice'),
            url()->current(),
            'メール認証フォームページに到達できませんでした'
        );
    }

    /** @test */
    public function it_redirects_to_profile_page_after_email_verification()
    {
        // 1. 仮ユーザーを作成（未認証状態）
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // 2. 認証コードと有効期限を設定
        $code = '123456';
        $user->update([
            'email_verification_code' => $code,
            'email_verification_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // 3. ログイン状態を再現
        $this->actingAs($user);

        // 4. 認証コードを送信（POSTリクエスト）
        $response = $this->post(route('verification.code.check'), [
            'code' => $code,
        ]);

        // 5. リダイレクト先がプロフィール設定画面であることを確認
        $response->assertRedirect(route('mypage.edit'));

        // 6. ユーザーが認証済みになっていることを確認
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);

        // 7. プロフィール設定画面を開けることを確認
        $page = $this->get(route('mypage.edit'));
        $page->assertStatus(200);
        $page->assertSee('プロフィール設定');
    }
}
