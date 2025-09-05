@extends('layouts.app_logout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

    <div class="profile_page">
        <div class="profile_header">
            <div class="profile_header_1">
                <img src="{{ asset($user->user_image) }}" alt="プロフィール画像" class="user_image_css">
                <h2 class="user_name_css">{{ $user['name'] }}</h2>
            <form action="/mypage/profile" method="post" class="user_edit_css1">
                @csrf
                    <input type="submit" class="user_edit_css2" value="プロフィール編集">
            </form>
        </div>

        <div class="profile_header_2">
            <a href="/mypage?page=sell" class="sell_items">出品した商品</a>
            <a href="/mypage?page=buy" class="buy_items">購入した商品</a>
        </div>

        </div>

            <!-- 出品した商品リスト -->
                @if ($page === 'sell')
                @if ($items->isEmpty())
                    <p>出品した商品はありません。</p>
                @else
        <div class="items_select">
            @foreach ($items as $item)
                <div class="items_select_all">
                    <a href="/item/{{ $item->id }}">
                        <img src="{{ asset($item->item_image) }}" alt="商品写真">
                    </a>
                        <label>{{ $item->name }}</label>
                </div>
            @endforeach
        </div>
                @endif



            <!-- 購入した商品リスト -->
                @elseif ($page === 'buy')
                @if ($items->isEmpty())
                    <p>購入した商品はありません。</p>
                @else
        <div class="items_select">
            @foreach ($items as $item)
                <div class="items_select_all">
                    <a href="/item/{{ $item->item->id }}">
                        <img src="{{ asset($item->item->item_image) }}" alt="商品写真">
                    </a>
                        <label>{{ $item->item->name }}</label>
                </div>
            @endforeach
            </div>
                @endif

            <!-- デフォルトの表示（buyがない場合） -->
                @else
                    <p>デフォルトの表示、出品した商品</p>
            <!-- 出品した商品の表示ロジックをここに書く -->
                @endif

    </div>




@endsection