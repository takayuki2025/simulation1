@extends('layouts.app_register')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login_page">
<h2 class="title">ログイン</h2>
<form action="login" method="POST">
    <label class="label_form_1">メールアドレス</label>
    <input type="text" class="mail_form" name="email">
    <label class="label_form_2">パスワード</label>
    <input type="text" class="password_form" name="password">
    <div class="submit">
    <input type="submit" class="submit_form" value="ログインする">
</div>
</form>
    <a class="register_page" href="{{ route('register') }}">会員登録はこちら</a>

</div>





@endsection