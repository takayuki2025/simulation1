@extends('layouts.app_register')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="login_page">
<h2 class="title">会員登録</h2>
<form action="login" method="POST">
    <label class="label_form_1">ユーザー名</label>
    <input type="text" class="name_form" name="name">
    <label class="label_form_2">メールアドレス</label>
    <input type="text" class="email_form" name="email">
    <label class="label_form_3">パスワード</label>
    <input type="text" class="password_form" name="password">
    <label class="label_form_4">確認用パスワード</label>
    <input type="text" class="password_form" name="">
    <div class="submit">
    <input type="submit" class="submit_form" value="登録する">
</div>
</form>
    <a class="register_page" href="{{ route('login') }}">ログインはこちら</a>

</div>





@endsection