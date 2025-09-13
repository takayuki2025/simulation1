<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Good;

class Id08_02Test extends TestCase
{
    use RefreshDatabase;


            public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }







    /**
     * いいねアイコンの色が、登録・解除に応じて正しく変化することをテストします。
     *
     * @return void
     */
    // public function test_favorite_icon_changes_color_on_toggle()
    // {
    //     // 1. テスト用のユーザーと商品を作成
    //     $user = User::factory()->create();
    //     $item = Item::factory()->create();

    //     // ユーザーを認証状態にする
    //     $this->actingAs($user);

    //     // 2. いいね登録前のテスト
    //     // いいねする前の商品詳細ページにアクセス
    //     $response = $this->get(route('item_detail', ['item_id' => $item->id]));

    //     // いいねしていないハート（&#x2661;）が表示されていることを確認
    //     $response->assertSee('&#x2661;');
    //     $response->assertDontSee('&#x2665;');

    //     // 3. いいね登録後のテスト
    //     // いいね登録のルートにPOSTリクエストを送信
    //     $this->post(route('item.favorite', ['item' => $item->id]));

    //     // 再度商品詳細ページにアクセスして、表示の変化を確認
    //     $response = $this->get(route('item_detail', ['item_id' => $item->id]));

    //     // いいねしたハート（&#x2665;）が表示されていることを確認
    //     $response->assertSee('&#x2665;');
    //     $response->assertDontSee('&#x2661;');

    //     // 4. いいね解除後のテスト
    //     // いいね解除のルートにPOSTリクエストを送信
    //     $this->post(route('item.unfavorite', ['item' => $item->id]));

    //     // 再度商品詳細ページにアクセスして、表示の変化を確認
    //     $response = $this->get(route('item_detail', ['item_id' => $item->id]));

    //     // いいねしていないハート（&#x2661;）に戻っていることを確認
    //     $response->assertSee('&#x2661;');
    //     $response->assertDontSee('&#x2665;');
    // }
}