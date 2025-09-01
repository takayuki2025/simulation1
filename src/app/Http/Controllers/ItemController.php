<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return view('front_page',compact('items'));
    }

        public function profile_show(Request $request)
    {
        if (Auth::check()) {
        $user = Auth::user();
        }
        return view('profile',compact('user'));
    }

        public function item_sell_show(Request $request)
    {
        if (Auth::check()) {
        $items = Item::all();
        }
        return view('item_sell',compact('items'));
    }

        public function profile_revise(Request $request)
    {
        if (Auth::check()) {
        $user = Auth::user();
        }
        return view('profile_edit',compact('user'));
    }

    // public function profile_show()
    // {
    //     $user = Auth::user();

    //     return view('profile_edit',compact('user'));
    // }

    public function profile_update(ProfileRequest $request)
    {
        if (Auth::check()) {
        $user = Auth::user();
        $user->update($request->only('name', 'post_number', 'address', 'building'));
        }

        $items = Item::all();

        return view('front_page',compact('items'));
    }

public function showOneTimePage()
{
    // ユーザーがログインしているかを確認します
    if (!Auth::check()) {
        // ログインしていない場合は、ログインページにリダイレクト
        return redirect()->route('login');
    }

    // ユーザーが認証済みであることが分かったので、安全にユーザー情報を取得できます
    $user = Auth::user();

    // ユーザーがすでに一度アクセス済みかチェック
    if ($user->first_time_access) {
        // 既にアクセス済みならホームページにリダイレクト
        return redirect()->route('front_page');
    }

    // フラグをtrueに更新して、初回アクセス済みとマーク
    $user->update(['first_time_access' => true]);

    // ユーザーデータをビューに渡して表示
    return view('profile_edit', compact('user'));
}


}
