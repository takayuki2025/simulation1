@extends('layouts.app_logout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endsection

@section('content')
<div class="item_detail_contents">
<div class="item_detail_image">
    <img class="item_detail_image1" src="/pictures/Armani+Mens+Clock.jpg" alt="会社のロゴ">
</div>
<div class="information">
<div class="item_detail_name">
    <h2>商品名</h2>
</div>
<div class="item_detail_brand">
    <p>ブランド名</p>
</div>
<div class="item_detail_price">
    <h2>金額</h2>
</div>
<div class="item_detail_icon">
    <p>いいねマーク　</p>
    <p>コメントマーク</p>
</div>
<div class="item_detail_form">
    <form action="/purchase" method="get">
        @csrf
    <input type="submit" value="購入手続きへ">
</form>
</div>
<div class="item_detail_explain">
    <h2>商品説明</h2>
</div>
<div class="item_detail_category">
<h3>カテゴリー　</h3>
<h3>カテゴリーの種類</h3>
</div>
<div class="item_detail_condition">
    <h3>商品の状態　</h3>
<h3>良好</h3>
</div>
<div class="item_detail_comment_history">
<h3>コメント</h3>


</div>
<div class="item_detail_comment_form">
<h3>商品へのコメント</h3>
<form action="" method="post" >
    @csrf
    <textarea name="comment" rows="4" cols="40"></textarea>
<input type="submit" value="コメントを送信する" class="comment_submit">
</form>
</div>




</div>






</div>

@endsection