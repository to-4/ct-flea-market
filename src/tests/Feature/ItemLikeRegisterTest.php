<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ItemLikeRegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_registers_like_when_user_presses_like_button(): void
    {
        // 1. 準備：ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name'      => 'トートバッグ',
            'image_url' => 'https://example.com/item.jpg',
        ]);

        // 2. ログイン状態をシミュレート
        $this->actingAs($user);

        // 3. いいね実行（POST）
        $response = $this->post(route('items.toggle-like', ['id' => $item->id]));

        // 4. ステータス確認（リダイレクト想定）
        $response->assertStatus(302);

        // 5. データベース確認：likes テーブルに登録されたか
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 6. 画面表示確認：いいね数が1に増加している
        $response = $this->followingRedirects()->get(route('items.show', $item->id));
        $response->assertSee('☆', false);
        $response->assertSee('<span class="count">1</span>', false);
    }

    #[Test]
    public function it_changes_icon_color_when_user_liked_the_item(): void
    {
        // 1. 準備：ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name'      => 'トートバッグ',
            'image_url' => 'https://example.com/item.jpg',
        ]);

        // 2. ログイン状態にする
        $this->actingAs($user);

        // 3. 商品に「いいね」を登録
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 4. 商品詳細ページを開く
        $response = $this->get(route('items.show', ['id' => $item->id]));

        // 5. ステータス確認
        $response->assertStatus(200);

        // 6. HTML内の like-button に liked クラスが含まれていることを確認
        $response->assertSee('class="action like-button liked"', false);

        // 7. アイコンといいね数が正しく表示されていることも確認
        $response->assertSee('☆', false);
        $response->assertSee('<span class="count">1</span>', false);
    }

    #[Test]
    public function it_removes_like_when_user_presses_like_button_again(): void
    {
        // 1. 準備：ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name'      => 'トートバッグ',
            'image_url' => 'https://example.com/item.jpg',
        ]);

        // 2. ログイン状態を再現
        $this->actingAs($user);

        // 3. 初期状態：すでにいいね済み（likesテーブルに登録）
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 4. いいね解除リクエスト（再押下）
        $response = $this->post(route('items.toggle-like', ['id' => $item->id]));

        // 5. ステータス確認（リダイレクト想定）
        $response->assertStatus(302);

        // 6. likes テーブルからレコードが削除されていることを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 7. ページ再取得後、いいね数が減少（0件）になっていることを確認
        $response = $this->followingRedirects()->get(route('items.show', $item->id));
        $response->assertSee('<span class="count">0</span>', false);
        $response->assertDontSee('class="action like-button liked"', false);
    }
}
