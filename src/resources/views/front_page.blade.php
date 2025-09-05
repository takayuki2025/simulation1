@extends('layouts.app_logout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/front_page.css') }}">
@endsection

@section('content')

    <div class="main_contents">
            <div class="alert alert-success">
        {{ session('success') }}
    </div>

        <div class="main_select">
            <a href="/" class="recs">おすすめ</a>
            <a href="/?tab=mylist" class="mylists">マイリスト</a>
        </div>


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


    </div>





@endsection