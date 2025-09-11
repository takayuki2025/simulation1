<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .verification-container {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .verification-container h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        .verification-container p {
            color: #6b7280;
            margin-bottom: 2rem;
        }
        .verification-button, .resend-button {
            width: 100%;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .verification-button {
            background-color: #3b82f6;
            color: #fff;
        }
        .verification-button:hover {
            background-color: #2563eb;
        }
        .resend-button {
            background-color: #e5e7eb;
            color: #4b5563;
            margin-top: 1rem;
        }
        .resend-button:hover {
            background-color: #d1d5db;
        }
        .status-message {
            margin-top: 1rem;
            color: #16a34a; /* Green color for success message */
            font-weight: 500;
        }
        .info-message {
            color: #374151;
            font-weight: normal;
        }
        .logout-link {
            display: block;
            margin-top: 1.5rem;
            color: #6b7280;
            text-decoration: none;
        }
        .logout-link:hover {
            color: #1f2937;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <h1>メールアドレスを認証してください</h1>
        <p>続行するには、登録したメールアドレスに送られたリンクをクリックしてください。</p>

        <!-- 成功メッセージを表示 -->
        @if (session('status') === 'verification-link-sent')
            <div class="status-message">
                新しい認証リンクが、あなたのメールアドレスに送信されました。
            </div>
        @else
            <div class="info-message">
                メールが見つからない場合は、以下のボタンから再送を試すか、メールアプリを開いて確認してください。
            </div>
        @endif

        <!-- 「認証はこちらから」ボタン（MailHogにリダイレクト） -->
        <a href="http://localhost:8025" target="_blank" class="verification-button">認証はこちらから</a>

        <!-- 「認証メールを再送」ボタン -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="resend-button">認証メールを再送</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-link">ログアウト</button>
        </form>
    </div>
</body>
</html>