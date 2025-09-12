<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\OrderHistory;

class Id10_02Test extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * Stripe決済で商品を購入できることをテスト
     */
    public function test_authenticated_user_can_purchase_with_stripe_payment()
    {
        // 事前準備: 認証済みユーザーと購入対象の商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['remain' => 1, 'price' => 1000]);

        // 実行: 認証済みユーザーとして購入リクエストを送信
        $response = $this->actingAs($user)->post('/purchase/create', [
            'item_id' => $item->id,
            'payment' => 'Stripe', // Stripe決済であることを示す
            'address' => '東京都',
        ]);

        // 検証1: リダイレクトが成功し、ステータスコードが302であることを確認
        $response->assertStatus(302);
        $response->assertRedirect('/thanks_buy');

        // 検証2: order_historiesテーブルにデータが追加されたことを確認
        $this->assertDatabaseHas('order_histories', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => 'Stripe',
            'address' => '東京都',
        ]);
        
        // 検証3: itemsテーブルの在庫が1減っていることを確認
        $this->assertDatabaseHas('items', ['id' => $item->id, 'remain' => 0]);
    }
}