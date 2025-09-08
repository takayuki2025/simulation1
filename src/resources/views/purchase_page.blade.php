<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <input type="hidden" name="item_id" value="{{ $item->id }}">

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
        const stripe = Stripe('pk_test_51S4djbL5FmW737EdtTZZSncxQjYuIhaB4FxsBjg7Of1Lr7mYkT74ZU2yauWUY2t0aPPGyIIydYnx8VfxrLs755yl0028SjCRfD');
        const elements = stripe.elements();
        const card = elements.create('card');

        // Stripe ElementsをDOMにマウント
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

            if (paymentSelect.value === 'カード支払い') {
                // カード支払いの場合のみStripeトークンを生成
                const { token, error } = await stripe.createToken(card);

                if (error) {
                    // エラー表示
                    cardErrors.textContent = error.message;
                } else {
                    // トークンをフォームに隠しフィールドとして追加
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);

                    // フォームを送信
                    form.submit();
                }
            } else {
                // その他の支払い方法の場合、直接フォームを送信
                form.submit();
            }
        });
    </script>
</body>
</html>