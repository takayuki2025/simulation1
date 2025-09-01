@extends('layouts.app_logout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile_page">
<h1>プロフィール画面</h1>
{{ $user['name'] }}
<form action="/mypage/profile" method="get">
    @csrf
<input type="submit" class="profile_edit_submit" value="プロフィール編集">
</form>


</div>


@endsection