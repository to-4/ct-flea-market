<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ItemCommentSendTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_allows_logged_in_user_to_send_comment(): void
    {
        // 1. ユーザーと商品を作成
        $user = User::factory()->create(['name' => '太郎']);
        $item = Item::factory()->create(['name' => 'トートバッグ']);

        // 2. ログイン状態を再現
        $this->actingAs($user);

        // 3. コメント投稿データを準備
        $formData = ['body' => 'とても気に入りました！'];

        // 4. コメント送信（POST）
        $response = $this->post(route('comment.store', ['item' => $item->id]), $formData);

        // 5. リダイレクト確認
        $response->assertStatus(302);
        $response->assertRedirect(route('items.show', $item->id));

        // 6. DBにコメントが保存されているか確認
        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'body'    => 'とても気に入りました！',
        ]);

        // 7. コメント数が1件になっていることを確認
        $this->assertEquals(1, Comment::count());

        // 8. コメント内容がページ上に表示されることを確認
        $response = $this->followingRedirects()->get(route('items.show', $item->id));
        $response->assertSeeText('とても気に入りました！');
        $response->assertSeeText('太郎');
    }

    #[Test]
    public function guest_user_cannot_send_comment(): void
    {
        // 1. 準備：コメント対象の商品を作成
        $item = Item::factory()->create(['name' => 'トートバッグ']);

        // 2. コメントフォーム内容を定義
        $formData = ['body' => 'とても気に入りました！'];

        // 3. ログインしていない状態でコメント送信（POST）
        $response = $this->post(route('comment.store', ['item' => $item->id]), $formData);

        // 4. ステータス確認：ログインページへリダイレクト
        $response->assertRedirect(route('login'));

        // 5. データベース確認：コメントが保存されていない
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'body'    => 'とても気に入りました！',
        ]);

        // 6. コメント数が0件であることを確認
        $this->assertEquals(0, Comment::count());
    }

    #[Test]
    public function it_shows_validation_error_when_comment_is_empty(): void
    {
        // 1. 準備：ログインユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => 'トートバッグ']);

        // 2. ログイン状態を再現
        $this->actingAs($user);

        // 3. 空コメントデータを準備
        $formData = ['content' => ''];

        // 4. コメント送信（POST）
        $response = $this->post(route('comment.store', ['item' => $item->id]), $formData);

        // 5. ステータス確認（バリデーションエラー時は302リダイレクト）
        $response->assertStatus(302);

        // 6. セッションにエラーメッセージが格納されているか確認
        $response->assertSessionHasErrors([
            'body' => 'コメントを入力してください',
        ]);

        // 7. コメントがDBに登録されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        // 8. リダイレクト後、エラーメッセージが画面に表示されるか確認
        $response = $this->followingRedirects()->get(route('items.show', $item->id));
        $response->assertSee('コメントを入力してください');
    }

    #[Test]
    public function it_shows_validation_error_when_comment_is_too_long(): void
    {
        // 1. 準備：ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => 'トートバッグ']);

        // 2. ログイン状態を再現
        $this->actingAs($user);

        // 3. 255文字のコメントデータを生成
        $longComment = str_repeat('あ', 255);
        $formData = ['body' => $longComment];

        // 4. コメント送信（POST）
        $response = $this->post(route('comment.store', ['item' => $item->id]), $formData);

        // 5. ステータス確認（302リダイレクト想定）
        $response->assertStatus(302);

        // 6. セッションにバリデーションエラーが格納されているか確認
        $response->assertSessionHasErrors([
            'body' => 'コメントは254文字以内で入力してください',
        ]);

        // 7. コメントがDBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        // 8. ページ上にエラーメッセージが表示されているか確認
        $response = $this->followingRedirects()->get(route('items.show', $item->id));
        $response->assertSee('コメントは254文字以内で入力してください');
    }
}
