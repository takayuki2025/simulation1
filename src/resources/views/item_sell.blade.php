@extends('layouts.app_logout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_sell.css') }}">
@endsection


@section('content')

<div class="item_sell_contents">
<div class="item_sell_contents_box">

<div class="small_box">

<h1 class="item_sell_contents_box_title">商品の出品</h1>

<label class="item_sell_contents_box_imagetitle">商品画像</label>

<form action="/upload" enctype="multipart/form-data" method="post" class="item_sell_contents_box_line">
    @csrf

    <button type="button" class="upload_submit" onclick="document.getElementById('fileInput').click()">ファイルを選択してアップロード</button>

    <input type="file" name="item_image" id="fileInput" style="display: none;">

    @if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

</form>

<script>
    // JavaScriptでファイルが選択されたら自動でフォームを送信
    const fileInput = document.getElementById('fileInput');
    fileInput.addEventListener('change', function() {
        // ファイルが選択されているか確認
        if (this.files.length > 0) {
            // 親のフォームを送信
            this.form.submit();
        }
    });
</script>

<!-- @if(session('image_path'))
    <p>画像のパス： {{ session('image_path') }}</p>

    <img src="{{ asset(session('image_path')) }}" alt="アップロードされた画像">
@endif -->
<!-- <img src="{{ asset('storage/item_images/test.png') }}" alt="アップロードされた画像"> -->
<div class="sell_title1">
    <h2>商品の詳細</h2>
</div>

        <form action="/thanks_sell" method="post">
    @csrf


<div class="sell_title1_1">
    <label>カテゴリー</label>

<br>
<br>
        <select name="category" class="select_box">
            <option value="ファッション">ファッション</option>
</select>
<br>
<br>
<br>
<br>
<br>

<div class="sell_title1_2">
    <label>商品の状態</label>
        <select name="condition" class="select_box">
            <option value="">選択してください</option>
            <option value="良好">良好</option>
            <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
            <option value="やや傷や汚れあり">やや傷や汚れあり</option>
            <option value="状態が悪い">状態が悪い</option>
</select>
</div>
</div>
<div class="sell_title2">
    <h2>商品と説明</h2>
</div>

<div class="sell_title2_1">
<label>商品名</label>
<input type="text" name="name" class="sell_item_form">
</div>
<div class="sell_title2_2">
<label>ブランド名</label>
<input type="text" name="brand" class="sell_item_form">
</div>
<div class="sell_title2_3">
<label>商品の説明</label>
<textarea name="explain" class="sell_item_form_textarea"></textarea>
</div>
<div class="sell_title2_4">
<label>販売価格</label>
<input type="text" name="price" class="sell_item_form">
</div>

<div class="sell_title3">
        <input type="submit" value="出品する" class="sell_item_submit">

        <input type="hidden" name="item_image" value="{{ session('image_path') }}">
    </form>
</div>


</div>

</div>
</div>































@endsection