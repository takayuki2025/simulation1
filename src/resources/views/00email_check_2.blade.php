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
</style>
</head>
<body>
<div class="verification-container">
<h1>メールアドレスを認証してください</h1>
<p>以下のボタンで認証を完了するか、再送ボタンを押してください。</p>

    <!-- 成功メッセージを表示 -->
    @if (session('status'))
        <div class="status-message">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.complete') }}">
        @csrf
        <button type="submit" class="verification-button">認証を完了</button>
    </form>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="resend-button">認証メールを再送</button>
    </form>
</div>

</body>
</html>