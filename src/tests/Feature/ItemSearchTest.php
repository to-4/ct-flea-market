<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{

    use RefreshDatabase;

    #[Test]
    public function it_displays_items_matching_search_keyword_partially(): void
    {
        // 1. テストデータを作成
        $item1 = Item::factory()->create(['name' => 'トートバッグ']);
        $item2 = Item::factory()->create(['name' => 'ショルダーバッグ']);
        $item3 = Item::factory()->create(['name' => 'サングラス']);

        // 2. 検索キーワードを付けてリクエスト送信
        $response = $this->get(route('index', ['keyword' => 'バッグ']));

        // 3. 検索結果に「バッグ」を含む商品が表示されること
        $response->assertStatus(200);
        $response->assertSee('トートバッグ');
        $response->assertSee('ショルダーバッグ');

        // 4. 検索ワードを含まない商品は表示されないこと
        $response->assertDontSee('サングラス');
    }

    #[Test]
    public function it_keeps_search_keyword_when_navigating_to_mylist(): void
    {
        // 1. ログインユーザーを作成
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. 商品を作成
        $item1 = Item::factory()->create(['name' => 'トートバッグ']);
        $item2 = Item::factory()->create(['name' => 'ショルダーバッグ']);
        $item3 = Item::factory()->create(['name' => 'サングラス']);

        // 3. 「バッグ」で検索
        $response = $this->get(route('index', ['keyword' => 'バッグ']));
        $response->assertStatus(200);
        $response->assertSeeText    ('トートバッグ');
        $response->assertSeeText    ('ショルダーバッグ');
        $response->assertDontSeeText('サングラス');

        // 4. 検索したアイテムをマイリスト登録
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        // 5. マイリスト画面に遷移（検索キーワード付き）
        $response = $this->get(route('index', ['tab' => 'mylist', 'keyword' => 'バッグ']));
        $response->assertStatus(200);

        // 6. 検索フォームの value にキーワードが保持されていること
        // assertSee(…, false) で HTMLエスケープ無視
        $response->assertSee        ('value="バッグ"', false);
        $response->assertSeeText    ('トートバッグ');
        $response->assertDontSeeText('ショルダーバッグ');
        $response->assertDontSeeText('サングラス');
    }
}
