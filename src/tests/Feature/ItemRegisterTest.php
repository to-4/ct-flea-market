<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemCondition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemRegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_new_item_correctly()
    {
        // 1. 出品者ユーザーを作成
        $user = User::factory()->create();

        // 2. ログイン状態を再現
        $this->actingAs($user);

        // 3. 出品画面にアクセス（確認用）
        $response = $this->get(route('sell'));
        $response->assertStatus(200);

        // 4. テスト用のカテゴリ・商品の状態を作成
        $category  = Category::     factory()->create(['name' => 'バッグ']);
        $condition = ItemCondition::factory()->create(['name' => '新品']);

        // 5. 仮想ストレージを作成
        // /storage/framework/testing/disks/public に一時ディレクトリを作る
        Storage::fake('public');

        // 6. ダミーファイル作成（実ファイルを生成）
        //    1x1ピクセルの本物のPNGデータ（Base64デコード済み）
        $tempPath = Storage::disk('public')->path('dummy.png');
        $realPng  = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAA
            AAC0lEQVR42mP8/x8AAwMBAK0KU7EAAAAASUVORK5CYII='
        );
        file_put_contents($tempPath, $realPng);

        // 7. UploadedFile インスタンスを作成
        $file = new UploadedFile(
            $tempPath,      // 1. ファイルの実パス
            'dummy.png',    // 2. 元のファイル名
            'image/png',    // 3. MIMEタイプ
            null,           // 4. エラーコード（省略OK）
            true            // 5. テストモード：true にしないと mimes ルールで落ちる
        );

        // 7. 商品情報をフォーム送信（出品登録処理を再現）
        //    ※ image_url のバリデーションでは、 mimes (アップロードファイル検証)が用いられている為
        //       ダミーファイルを疑似アップロード
        $formData = [
            'name'              => 'テストバッグ',
            'brand_name'        => 'テストブランド',
            'description'       => 'これはテスト用の説明文です。',
            'price'             => 15000,
            'user_id'           => $user->id,
            'categories'        => [$category->id],
            'item_condition_id' => $condition->id,
            'image_url'         => $file,
        ];

        $response = $this->followingRedirects()
            ->post(
                route('sell.post'),
                $formData,
                ['Content-Type' => 'multipart/form-data']
            );
        $response->assertStatus(200); // 200: リダイレクト後 の GET

        // 6. itemsテーブルにデータが保存されていることを確認
        //    ※ image_url は、/storage/items/***.png となるので、モデルから内容を取得
        $item = Item::latest()->first();
        $this->assertDatabaseHas('items', [
            'user_id'           => $user->id,
            'item_condition_id' => $condition->id,
            'name'              => 'テストバッグ',
            'brand_name'        => 'テストブランド',
            'description'       => 'これはテスト用の説明文です。',
            'price'             => 15000,
            'image_url'         => $item->image_url,
        ]);

        // 7. category_item テーブルにデータが保存されていることを確認
        $this->assertDatabaseHas('category_item', [
            'item_id'     => $item->id,
            'category_id' => $category->id,
        ]);

        // 8. ファイルが保存されているか確認
        //    Strorage::fake('public') により
        //    '/storage/framework/testing/disks/public/' に保存
        //    上記の相対パスで確認するため、
        //    image_url (/storage/items/**.png)から /storage/ を除外して確認
        Storage::disk('public')->assertExists(
            str_replace('/storage/', '', $item->image_url)
        );
    }
}
