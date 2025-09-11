<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メールアドレスを確認してください</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f7fafc;
            color: #4a5568;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        .heading {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .message {
            margin-bottom: 1rem;
        }
        .resend-form {
            display: inline;
        }
        .resend-button {
            background-color: #4c51bf;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="heading">メールアドレスを確認してください</h1>
        <p class="message">
            新しいメールアドレス宛に、認証リンクを送信しました。メールをご確認ください。
        </p>
        <p class="message">
            メールが届かない場合は、以下のボタンをクリックして再送信してください。
        </p>
        <form class="resend-form" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <button type="submit" class="resend-button">認証メールを再送信</button>
            </div>
        </form>
    </div>
</body>
</html>