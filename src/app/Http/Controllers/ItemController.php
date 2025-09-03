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

        public function item_detail_show($id)
    {
        $item = Item::findOrFail($id);

        // 商品が存在しない場合のエラー処理（推奨）
            if (!$item) {
        // 例として、404ページを表示
            abort(404);
    }
        return view('item_detail',compact('item' ,'id'));
    }

            public function item_buy_show($id)
    {
        $user = Auth::user();
        $item = Item::find($id);
        if (!$item) {
            abort(404);
        }

        return view('item_buy',[
            'item' => $item,
            'id' => $item->id,
            'user' => $user,
        ]);
    }

            public function item_purchase_edit($id)
    {
            // 未定義エラーを防ぐため、$userをnullで初期化
    $user = null;

        if (Auth::check()) {
        $user = Auth::user();
        }
            // URLにIDがあるので、アイテムもビューに渡すべきです
    $item = Item::findOrFail($id);

        return view('address',compact('user', 'item'));
    }

        public function profile_revise(Request $request)
    {
        if (Auth::check()) {
        $user = Auth::user();
        }
        return view('profile_edit',compact('user'));
    }

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


public function item_image_upload(Request $request){
    //  $img=$request->imgpath;  //formで設置したname名
     $filename=$request->item_image->getClientOriginalName();

    $img=$request->item_image->storeAs('public/item_images',$filename);

    $img = str_replace('public/', '', $img);
    //  dd($img);
    return redirect()->back()->with('success', 'フ！')->with('image_path', 'storage/' .$img);
}

public function thanks_buy_create(Request $request)
{
    $item = $request->only(['name','price','brand','explain','condition','category','item_image']);

     // 画像がアップロードされているか確認
//   if ($request->hasFile('item_image')) {
//     // ファイル名が重複しないようにユニークなファイル名を生成
//     $filename = time() . '_' . $request->item_image->getClientOriginalName();
    
//     // 画像を保存し、そのパスを取得
//     $path = $request->item_image->storeAs('public/item_images', $filename);
    
    // データベースに保存する画像パスを追加
    // $item['item_image'] = $img;

//   }



  $item['user_id'] = auth()->id();

  Item::create($item);

  return redirect('/')->with('success', '商品を登録しました。');
}
}
