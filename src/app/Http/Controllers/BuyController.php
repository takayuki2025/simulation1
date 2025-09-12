<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;


use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session;

class BuyController extends Controller
{
    /**
     * 商品購入処理を実行する
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // 支払い方法を取得
        $paymentMethod = $request->input('payment');
        $itemId = $request->input('item_id');
        $item = Item::findOrFail($itemId);
        $address = $request->input('address');

        // 価格を整数に変換
        $priceInYen = (int) $item->price;

        // 価格がStripeの最小支払い額(50円)未満でないかチェック
        if ($priceInYen < 50) {
            return response()->json(['message' => 'お支払い金額は¥50以上である必要があります。'], 400);
        }

        // バリデーションチェック
        if ($item->remain < 1) {
            return response()->json(['message' => 'この商品は在庫がありません。'], 400);
        }
        
        if (empty($address)) {
            return response()->json(['message' => '配送先住所が入力されていません。'], 400);
        }
        
        // Stripe決済の場合の処理
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $priceInYen, // 整数に変換した価格を使用
                'currency' => 'jpy',
                'payment_method' => $request->input('payment_method_id'),
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            // 決済成功時にデータベースに購入情報を保存
            OrderHistory::create([
                'payment' => 'カード支払い',
                'user_id' => Auth::id(),
                'item_id' => $itemId,
                'address' => $request->input('address') // 配送先情報を保存
            ]);

            // 在庫を減らす
            $item->remain = $item->remain - 1;
            $item->save();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['message' => '決済エラー: ' . $e->getMessage()], 500);
        }
    
    }

    /**
     * 決済成功後の共通処理
     *
     * @param Request $request
     * @return void
     */
    private function handleSuccessfulPayment(Request $request)
    {
        $paymentMethod = $request->input('payment');
        $itemId = $request->input('item_id');
        $userId = auth()->id();

        $order = [
            'payment' => $paymentMethod,
            'user_id' => $userId,
            'item_id' => $itemId,
        ];

        OrderHistory::create($order);

        $item = Item::findOrFail($itemId);

        if ($item->remain > 0) {
            $item->remain = $item->remain - 1;
            $item->save();
        } else {
            // 在庫がない場合の処理
            // このケースはフロントエンドで制御することが望ましい
        }
    }

    /**
     * 決済完了ページを表示する
     */
    public function thanks_buy_show()
    {
        return view('thanks_buy');
    }

    /**
     * Stripe決済フォームページを表示する
     *
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function showStripePaymentForm($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('stripe_payment', compact('item', 'user'));
    }
}