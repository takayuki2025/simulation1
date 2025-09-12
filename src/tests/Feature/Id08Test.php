<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Good;

class Id08Test extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねアイコンを押すことによって、いいねした商品として登録・解除できることをテストします。
     *
     * @return void
     */
    public function test_user_can_toggle_favorite_on_item()
    {
        // 1. テスト用のユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーを認証状態にする
        $this->actingAs($user);

        // 2. いいね登録のテスト
        // いいねする前のgoodsテーブルにレコードがないことを確認
        $this->assertDatabaseMissing('goods', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // いいね登録ルートにPOSTリクエストを送信
        $response = $this->post(route('item.favorite', ['item' => $item->id]));

        // リクエストが成功し、元のページにリダイレクトされることを確認
        $response->assertRedirect();

        // いいね登録後のgoodsテーブルにレコードが追加されたことを確認
        $this->assertDatabaseHas('goods', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 3. いいね解除のテスト
        // 再度POSTリクエストを送信
        $response = $this->post(route('item.favorite', ['item' => $item->id]));

        // リクエストが成功し、元のページにリダイレクトされることを確認
        $response->assertRedirect();

        // いいね解除後のgoodsテーブルからレコードが削除されたことを確認
        $this->assertDatabaseMissing('goods', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}