<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f7fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }
        h1 {
            color: #2d3748;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        p {
            color: #4a5568;
            line-height: 1.6;
        }
        .resend-form {
            margin-top: 20px;
        }
        .resend-button {
            background-color: #4299e1;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
        }
        .resend-button:hover {
            background-color: #3182ce;
        }
        .logout-form {
            margin-top: 10px;
        }
        .logout-button {
            background: none;
            border: none;
            color: #718096;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>メール認証が必要です</h1>
        <p>ご登録ありがとうございます！続行する前に、メールに送信された認証リンクをクリックしてメールアドレスを確認してください。</p>
        <p>メールが届いていない場合は、以下のボタンをクリックして再送信してください。</p>

        @if (session('status') == 'verification-link-sent')
            <p style="color: green;">新しい認証リンクがメールアドレスに送信されました。</p>
        @endif

        <div class="resend-form">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="resend-button">認証メールを再送信</button>
            </form>
        </div>

        <div class="logout-form">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">ログアウト</button>
            </form>
        </div>
    </div>
</body>
</html>