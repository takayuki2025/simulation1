<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

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
        // 支払い方法がクレジットカードの場合の処理
        if ($request->payment === 'カード支払い') {
            // Stripe APIキーを設定
            // 環境変数からキーを取得することが推奨されます
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            try {
                // PaymentIntentを作成し、クライアントシークレットを返す
                $paymentIntent = PaymentIntent::create([
                    'amount' => Item::findOrFail($request->item_id)->price,
                    'currency' => 'jpy',
                    'payment_method' => $request->payment_method_id,
                ]);

                // 成功したクライアントシークレットをフロントエンドに返す
                return response()->json(['client_secret' => $paymentIntent->client_secret]);

            } catch (\Exception $e) {
                // 例外処理
                return response()->json(['message' => 'サーバー側でエラーが発生しました。' . $e->getMessage()], 500);
            }
        }
        
        // その他の支払い方法（例: コンビニ払い）の処理
        if ($request->payment === 'コンビニ払い') {
            $this->handleSuccessfulPayment($request);
            return redirect()->route('thanks_buy')->with('success', '商品を購入しました。');
        }

        // 支払い方法が選択されていない場合の処理
        return redirect()->back()->withErrors(['payment' => '支払い方法を選択してください。']);
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