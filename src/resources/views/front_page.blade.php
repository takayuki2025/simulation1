@extends('layouts.app_logout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/front_page.css') }}">
@endsection

@section('content')

<div class="main_contents">
<div class="main_select">
<a href="/" class="recs">おすすめ</a>
<a href="/" class="mylists">マイリスト</a>
</div>

<div class="items_select">
    <div class="items_select_all">
        <a href="/item">
<img src="/pictures/Armani+Mens+Clock.jpg" alt="会社のロゴ">
</a>
<label>腕時計</label>

</div>
    <div class="items_select_all">
<img src="/pictures/HDD+Hard+Disk.jpg" alt="会社のロゴ">
<label>HDD</label>
</div>
    <div class="items_select_all">
<img src="/pictures/iLoveIMG+d.jpg" alt="会社のロゴ">
<label>玉ねぎ３束</label>
</div>
    <div class="items_select_all">
<img src="/pictures/Leather+Shoes+Product+Photo.jpg" alt="会社のロゴ">
<label>革靴</label>
</div>
    <div class="items_select_all">
<img src="/pictures/Leather+Shoes+Product+Photo.jpg" alt="会社のロゴ">
<label>腕時計</label>
</div>

</div>
</div>

@endsection