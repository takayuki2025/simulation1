@extends('layouts.app_logout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endsection

@section('content')

    <div class="item_detail_contents">



        <div class="item_detail_image">
            <img class="item_detail_image1" src="{{ asset($item->item_image) }}" alt="商品写真">
        </div>

        <div class="information">
            <div class="item_detail_name">
                <h2>{{ $item->name }}</h2>
            </div>
            <div class="item_detail_brand">

                <p>ブランド名：{{ $item->brand }}</p>
            </div>
            <div class="item_detail_price">
                <h2>¥{{ $item->price}}</h2>
            </div>
            <div class="item_detail_icon">

                    <p>いいね</p>
                <form action="{{ route('item.favorite', ['item' => $item->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="favorite_button">
                        <span class="heart_icon">
                            @if ($isFavorited)
                                &#x2665; <!-- いいね済みハート -->
                            @else
                                &#x2661; <!-- いいねしていないハート -->
                            @endif
                        </span>
                    </button>
                    <span class="favorites_count">{{ $favoritesCount }}</span>
                </form>





                    <p>コメントマーク</p>
            </div>
            <div class="item_detail_form">
        <form action="{{ route('item_buy', ['item_id' => $item->id]) }}" method="get">
            @csrf
            <input type="submit" value="購入手続きへ" class="info_submit">
        </form>
        </div>
        <div class="item_detail_explain">
            <h2>商品説明</h2>
            <h3>{{ $item->explain }}</h3>
        </div>
        <div class="item_detail_category">
            <h3>カテゴリー　</h3>
                <h3>{{ $item->category }}</h3>
        </div>
        <div class="item_detail_condition">
            <h3>商品の状態　</h3>
                <h3>{{ $item->condition }}</h3>
        </div>
        <div class="item_detail_comment_history">
            <h2>コメント</h2>

            @forelse($comments as $comment)
                <div class="comment">
                    <p class="comment-text">{{ $comment->comment }}</p>
                    <small>投稿日時: {{ $comment->created_at->format('Y/m/d H:i') }}</small>
                </div>
            @empty
                <p>まだコメントはありません。</p>
@endforelse

        </div>
        <div class="item_detail_comment_form">
            <h2>商品へのコメント</h2>
        <form action="{{ route('comment_create') }}"  method="post" >
            @csrf
                <textarea name="comment" rows="5" cols="40"></textarea>
                <input type="submit" value="コメントを送信する" class="comment_submit">
                <input type="hidden" name="item_id" value="{{ $item->id }}">
        </form>
        </div>


        </div>



    </div>

@endsection


