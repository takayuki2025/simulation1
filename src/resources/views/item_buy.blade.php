@extends('layouts.app_logout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item_buy.css') }}">
@endsection

@section('content')
<div class="item_buy_contents">
        <div class="item_buy_lr">

        <div class="item_buy_l">
                <div class="item_buy_content1">
        <div class="item_buy_image">
                <img src="{{ asset($item->item_image) }}" alt="会社のロゴ">
                </div>
        <h3 class="item_name">{{ $item->name }}</h3>
        <h2 class="item_price">{{ $item->price }}</h2>
                </div>
                <div class="item_buy_content2">
                        <h4 class="item_pay">支払い方法</h4>
                <select id="pay" name="payment">
                        <option value="">支払いを選択してください</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カードし払い">カード支払い</option>
                </select>
                </div>
                <div class="item_buy_content3">
                        <div class="item_edit">
                        <h4 class="item_address">配送先</h4>
                        <a href="{{ route('address', ['id' => $id ?? '']) }}"  class="item_edit_a">変更する</a>
                        </div>
                        <h5 class="item_address_view1">{{ $user->post_number}}</h5>
                        <h5 class="item_address_view2">{{ $user->address }}</h5>
                </select>
                </div>
        </div>

        <div class="item_buy_r">
                <div class="item_buy_table">
                <table>
                        <tr class="line">

                                <th>商品代金</th>
                                <td>{{ $item->price }}</td>
                        </tr>
                        <tr>
                                <th>支払い方法</th>
                                <td>選択した方法</td>
                        </tr>
                </table>
                </div>
                <div class="item_buy_form">
                        <form action="thanks" method="POST">
                                @csrf
                                        <input type="submit" class="item_buy_submit" value="購入する">
                        </form>
                </div>




        </div>

</div>
@endsection