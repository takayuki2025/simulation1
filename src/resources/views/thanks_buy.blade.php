<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご購入ありがとうございます</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 p-8 flex items-center justify-center min-h-screen">
    <div class="container mx-auto p-8 bg-white rounded-xl shadow-lg max-w-lg text-center">
        <div class="text-green-500 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold mb-4 text-gray-800">ご購入ありがとうございます</h1>
        <p class="text-gray-600 mb-8">お客様の支払いは正常に完了しました。</p>
        <a href="{{ route('front_page') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
            トップページに戻る
        </a>
    </div>
</body>
</html>