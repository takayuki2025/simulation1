<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>購入ページ</title>
    <!-- Stripe.jsを読み込み -->
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            font-size: 2rem;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .item-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        p {
            margin: 5px 0;
            color: #555;
        }

        .form-row {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

        #card-errors {
            color: #fa755a;
            font-size: 14px;
            margin-top: 10px;
        }

        .purchase-button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .purchase-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>商品購入</h1>
        <div class="item-info">
            <p>商品名: {{ $item->name }}</p>
            <p>価格: ¥{{ number_format($item->price) }}</p>
        </div>

        <form action="{{ route('buy_create') }}" method="POST" id="payment-form">
            @csrf
            <!-- 必要なデータを隠しフィールドとして追加 -->
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="address" value="{{ Auth::user()->address }}">
            <input type="hidden" name="payment_method_id" id="payment_method_id">

            <div class="form-row">
                <label for="payment_select">支払い方法</label>
                <select name="payment" id="payment_select" class="StripeElement">
                    <option value="">支払い方法を選択</option>
                    <option value="カード支払い">カード支払い</option>
                    <option value="コンビニ払い">コンビニ払い</option>
                </select>
            </div>

            <div class="form-row" id="card-element-container" style="display: none;">
                <label for="card-element">
                    クレジットカード情報
                </label>
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
                <div id="card-errors" role="alert"></div>
            </div>

            <button type="submit" class="purchase-button">購入を確定する</button>
        </form>
    </div>

    <script>
        const stripe = Stripe('pk_test_51PJKvFL5FmW737EdJ8G3U2DkP3RzWfL2kX7jV5hTq1M1OQW4LwQ6OQJ8D8XbN5WpS1fM8Qp1X5f8R0o2R2t1');
        const elements = stripe.elements();
        const card = elements.create('card');

        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const paymentSelect = document.getElementById('payment_select');
        const cardElementContainer = document.getElementById('card-element-container');
        const cardErrors = document.getElementById('card-errors');

        // 支払い方法選択時のイベントリスナー
        paymentSelect.addEventListener('change', (event) => {
            if (event.target.value === 'カード支払い') {
                cardElementContainer.style.display = 'block';
            } else {
                cardElementContainer.style.display = 'none';
            }
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const paymentMethod = paymentSelect.value;
            const item_id = document.querySelector('input[name="item_id"]').value;
            const address = document.querySelector('input[name="address"]').value;

            // バリデーションチェック
            if (!paymentMethod) {
                cardErrors.textContent = '支払い方法を選択してください。';
                return;
            }
            // 住所が空の場合、プロフィール編集ページへのリンクを表示
            if (!address) {
                cardErrors.innerHTML = '住所情報がありません。<a href="{{ route('profile_edit') }}" style="color: blue; text-decoration: underline;">こちらからプロフィールを登録・編集</a>してください。';
                return;
            }
            if (!item_id) {
                cardErrors.textContent = '商品IDが指定されていません。';
                return;
            }

            let paymentMethodId = null;
            if (paymentMethod === 'カード支払い') {
                // カード支払いの場合、Stripe PaymentMethodを作成
                const { paymentMethod: stripePaymentMethod, error: createError } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: card,
                });

                if (createError) {
                    cardErrors.textContent = createError.message;
                    return;
                }
                paymentMethodId = stripePaymentMethod.id;
            }

            // サーバーにデータを送信
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment: paymentMethod,
                        payment_method_id: paymentMethodId,
                        item_id: item_id,
                        address: address,
                    }),
                });

                const result = await response.json();

                if (!response.ok) {
                    cardErrors.textContent = result.message || '予期せぬエラーが発生しました。';
                    return;
                }
                
                // 成功時のリダイレクト
                if (result.redirect_url) {
                    window.location.href = result.redirect_url;
                } else {
                    cardErrors.textContent = '予期せぬエラーが発生しました。';
                }

            } catch (error) {
                console.error('Error:', error);
                cardErrors.textContent = '通信中にエラーが発生しました。サーバーまたはネットワークを確認してください。';
            }
        });
    </script>
</body>
</html>