<?php

// いいね機能テスト

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Good;

class Id08Test extends TestCase
{
    use RefreshDatabase;


    //ID08-1(1),ID08-3(1)認証済みユーザーが商品詳細ページに移動して、いいねアイコンを押すことによって、いいねした商品として登録・解除できることをテストします。
    public function test_authenticated_user_can_view_page_and_submit_comment()
    {
        // 1. テスト用のユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id]);
        $commentText = 'これはテストコメントです。';

        // ユーザーを認証状態にする
        $this->actingAs($user);

        // 2. 商品詳細ページへのアクセスを検証
        $response = $this->get(route('item_detail', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertViewIs('item_detail');
        $response->assertSee($item->name);

        // 3. コメントを送信し、保存されることを検証
        $response = $this->post(route('comment_create'), [
            'item_id' => $item->id,
            'comment' => $commentText,
        ]);

        // 成功時のリダイレクトを確認
        $response->assertRedirect(route('item_detail', ['item_id' => $item->id]));
        $response->assertSessionHas('success', 'コメントが送信されました。');

        // データベースにコメントが保存されたことを確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => $commentText,
        ]);
    }

    //ID08-1(2)いいね合計値が増加するテスト
    public function test_good_count_increases_when_item_is_favorited()
    {
        // テスト用のユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーを認証状態にする
        $this->actingAs($user);

        // いいねする前のgoodsテーブルのレコード数を取得
        $initialGoodsCount = Good::count();

        // いいね登録ルートにPOSTリクエストを送信
        $this->post(route('item.favorite', ['item' => $item->id]));

        // いいね後のgoodsテーブルのレコード数を取得
        $finalGoodsCount = Good::count();

        // goodsテーブルのレコード数が1増加したことを確認
        $this->assertEquals($initialGoodsCount + 1, $finalGoodsCount);
    }

    //ID08-3(2)いいね合計値が減少するテスト。
    public function test_good_count_decreases_when_item_is_unfavorited()
    {
        // テスト用のユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーを認証状態にする
        $this->actingAs($user);

        // goodsテーブルにいいねレコードを事前に作成
        Good::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // いいね解除前のgoodsテーブルのレコード数を取得
        $initialGoodsCount = Good::count();

        // いいね解除ルートにPOSTリクエストを送信
        $this->post(route('item.favorite', ['item' => $item->id]));

        // いいね解除後のgoodsテーブルのレコード数を取得
        $finalGoodsCount = Good::count();

        // goodsテーブルのレコード数が1減少したことを確認
        $this->assertEquals($initialGoodsCount - 1, $finalGoodsCount);
    }
}