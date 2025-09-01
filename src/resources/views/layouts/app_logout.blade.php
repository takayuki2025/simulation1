<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
    <div class="header__inner">
    <img class="company" src="/image_icon/logo.svg" alt="会社名">
        <form action="" method="POST">
            <input type = "text" class="search_form" name="search_item" placeholder="何をお探しですか？">
        </form>
        @if (Auth::check())
        <div class="login_page0">
            <form action="/logout" method="post">
                @csrf

        <input type="submit" class="login_page_1" value="ログアウト">
        <!-- <button class="login_page_1">ログアウト</button> -->
            </form>

        <a class="login_page_2" href="{{ route('login') }}">マイページ</a>
        <a class="login_page_3" href="{{ route('login') }}">出品</a>
        </div>
    </div>
        @else
        <div class="login_page0">
        <a class="login_page_1" href="{{ route('login') }}">ログイン</a>
        <a class="login_page_2" href="{{ route('login') }}">マイページ</a>
        <a class="login_page_3" href="{{ route('login') }}">出品</a>
      </div>
        @endif
    </header>

    <main>
    @yield('content')
    </main>
</body>

</html>