<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品購入 - {{ $item->name }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }
        .StripeElement--focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
            border-color: #3b82f6;
        }
        .StripeElement--invalid {
            border-color: #ef4444;
        }
        .StripeElement--webkit-autofill {
            background-color: #fefcbf !important;
        }
    </style>
</head>
<body class="bg-gray-100 p-8 flex items-center justify-center min-h-screen">
    <div class="container mx-auto p-8 bg-white rounded-xl shadow-lg max-w-2xl">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">商品購入</h1>

        <!-- 商品情報セクション -->
        <div class="flex flex-col md:flex-row gap-8 mb-8 border-b pb-8">
            <div class="md:w-1/2">
                <img src="{{ asset($item->item_image) }}" alt="{{ $item->name }}" class="w-full h-auto rounded-lg shadow-md">
            </div>
            <div class="md:w-1/2 flex flex-col justify-center">
                <p class="text-xl font-semibold text-gray-700 mb-2">{{ $item->name }}</p>
                <p class="text-2xl font-bold text-gray-900">¥{{ number_format($item->price) }}</p>
                <p class="text-sm text-gray-500 mt-2">ブランド: {{ $item->brand }}</p>
            </div>
        </div>

        @if ($item->price < 50)
            <!-- 価格が50円未満の場合に表示するメッセージ -->
            <div class="text-center bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                <p class="font-bold">購入できません</p>
                <p>Stripeの規定により、お支払い金額は¥50以上である必要があります。恐れ入りますが、別の商品をご検討ください。</p>
            </div>
        @else
            <!-- 価格が50円以上の場合は既存のフォームを表示 -->
            <div id="loading-spinner" class="hidden text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
                <p class="mt-2 text-gray-600">決済処理中...</p>
            </div>

            <!-- 決済フォーム -->
            <form id="payment-form" class="space-y-6" action="{{ route('buy_create_stripe') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="address" value="{{ session('address') }}">
                <input type="hidden" name="payment_method_id" id="payment-method-id">

                <div>
                    <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">
                        クレジットカード情報
                    </label>
                    <div id="card-element" class="px-4 py-3 bg-gray-50 rounded-lg">
                        <!-- Stripe Elements がここにカードフォームを挿入します -->
                    </div>
                    <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors" id="submit-button">
                    購入を確定する
                </button>
            </form>
        @endif
    </div>

    <script type="text/javascript">
        // Stripeの公開鍵を設定
        const stripe = Stripe('pk_test_51S4djbL5FmW737EdtTZZSncxQjYuIhaB4FxsBjg7Of1Lr7mYkT74ZU2yauWUY2t0aPPGyIIydYnx8VfxrLs755yl0028SjCRfD');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const loadingSpinner = document.getElementById('loading-spinner');
        const cardErrors = document.getElementById('card-errors');
        
        // 価格が50円未満の場合はカードフォームをマウントしない
        if (form) {
            cardElement.mount('#card-element');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                submitButton.disabled = true;
                loadingSpinner.classList.remove('hidden');
                cardErrors.textContent = '';

                const { paymentMethod, error: createError } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });

                if (createError) {
                    cardErrors.textContent = createError.message;
                    submitButton.disabled = false;
                    loadingSpinner.classList.add('hidden');
                    return;
                }

                try {
                    const response = await fetch('{{ route('buy_create_stripe') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            payment: 'カード支払い',
                            payment_method_id: paymentMethod.id,
                            item_id: document.querySelector('input[name="item_id"]').value,
                            address: document.querySelector('input[name="address"]').value,
                        }),
                    });

                    const result = await response.json();
                    
                    if (!response.ok) {
                        cardErrors.textContent = result.message || '予期せぬエラーが発生しました。';
                        loadingSpinner.classList.add('hidden');
                        submitButton.disabled = false;
                        return;
                    }

                    if (result.success) {
                        window.location.href = '{{ route('thanks_buy') }}';
                    } else if (result.client_secret) {
                        const { paymentIntent, error: confirmError } = await stripe.confirmCardPayment(
                            result.client_secret, {
                                payment_method: {
                                    card: cardElement
                                }
                            }
                        );
                        
                        if (confirmError) {
                            cardErrors.textContent = confirmError.message;
                            loadingSpinner.classList.add('hidden');
                            submitButton.disabled = false;
                        } else if (paymentIntent.status === 'succeeded') {
                            window.location.href = '{{ route('thanks_buy') }}';
                        } else {
                            cardErrors.textContent = '決済処理中に予期せぬ状態になりました。';
                            loadingSpinner.classList.add('hidden');
                            submitButton.disabled = false;
                        }
                    } else {
                        cardErrors.textContent = result.message || '予期せぬエラーが発生しました。';
                        loadingSpinner.classList.add('hidden');
                        submitButton.disabled = false;
                    }
                } catch (fetchError) {
                    console.error('Error:', fetchError);
                    cardErrors.textContent = '通信中にエラーが発生しました。サーバーまたはネットワークを確認してください。';
                    loadingSpinner.classList.add('hidden');
                    submitButton.disabled = false;
                }
            });
        }
    </script>
</body>
</html>