<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\OrderHistory;
use App\Models\Comment;
use App\Models\Good;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // URLのGETパラメータ'tab'を取得。デフォルトは'all'
        $tab = $request->query('tab', 'all');

        if ($tab === 'mylist') {
            // 'mylist'タブの場合、いいねした商品を取得
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'ログインしてください。');
            }
            $items = Good::where('user_id', $user->id)->with('item')->get()->map(function ($good) {
                return $good->item;
            });
        } else {
            // 'all'タブ（またはデフォルト）の場合、全商品を取得
            $items = Item::all();
        }

        return view('front_page',compact('items', 'tab'));
    }




    public function scour(Request $request)
{
    $item_search = $request->input('all_item_search');

  $items = Item::ItemSearch($item_search)->get();


  return view('front_page', compact('items'));
}











        public function profile_show(Request $request)
    {

            $page = $request->input('page');

    $userId = Auth::id();
    $items = collect();
      $user = Auth::user();
        // ログイン状態を確認
    if (!$userId) {
        return redirect()->route('login')->with('error', 'ログインしてください。');
    }

      // pageの値に応じてデータを取得
    if ($page === 'sell') {
        $items = Item::where('user_id', $userId)->get();
   
    } elseif ($page === 'buy') {
        $items = OrderHistory::where('user_id', $userId)->with('item')->get();
  
    }else {
            // デフォルトの表示（出品した商品）
            $items = Item::where('user_id', $user->id)->get();
            // $page もデフォルト値を設定しておくと、ビューで扱いやすい
            $page = 'sell';
        }
        // if (Auth::check()) {
        // $user = Auth::user();
        // $userId = Auth::id();
        // $items = Item::where('user_id', $userId)->get();
        // }

        return view('profile',compact('user','items','page'));
    }




        public function item_sell_show(Request $request)
    {
        if (Auth::check()) {
        $items = Item::all();
        }
        return view('item_sell',compact('items'));
    }

        public function item_detail_show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $item_id = $item->id;
        $comments = Comment::where('item_id',$item_id)->get();



                // ログイン中のユーザー情報を取得
        $user = Auth::user();
        $isFavorited = false; // デフォルト値を`false`に設定
        $favoritesCount = Good::where('item_id', $item->id)->count();
        // dd($comments);

                if ($user) {
            $isFavorited = Good::where('item_id', $item->id)
                ->where('user_id', $user->id)
                ->exists(); // `exists()`は真偽値を返すため効率が良い
        }
        // 商品が存在しない場合のエラー処理（推奨）
            if (!$item) {
        // 例として、404ページを表示
            abort(404);

    }
        return view('item_detail',compact('item' ,'item_id','comments', 'isFavorited','favoritesCount'));
    }

        public function favorite(Request $request, Item $item)
    {
        $user = Auth::user();

        if (!$user) {
            // ログインしていない場合はログインページにリダイレクト
            return redirect()->route('login')->with('error', 'いいね機能を利用するにはログインが必要です。');
        }

        // 既にいいねしているかチェック
        $existingGood = Good::where('item_id', $item->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingGood) {
            // 既にいいねしている場合は、いいねを削除
            $existingGood->delete();
        } else {
            // いいねしていない場合は、新しく作成
            Good::create([
                'item_id' => $item->id,
                'user_id' => $user->id,
            ]);
        }
        
        // 元のページに戻る（リダイレクト）
        return back();
    }


            public function item_buy_show($item_id)
    {

        $user = Auth::user();
        $item = Item::find($item_id);
        if (!$item) {
            abort(404);
        }

        return view('item_buy',[
            'item' => $item,
            'item_id' => $item->id,
            'user' => $user,
        ]);
    }



            public function item_purchase_edit($user_id,$item_id)
    {

                // 重要なセキュリティチェック：
    // URLのuser_idが認証済みユーザーのIDと一致することを確認する。
    if (Auth::id() != $user_id) {
        abort(403, 'Unauthorized action.');
    }


        $user = Auth::user();


            // URLにIDがあるので、アイテムもビューに渡すべきです
    $item = Item::findOrFail($item_id);
    //    dd($item);
        return view('address',compact('user','item_id','user_id','item'));
    }





            public function purchase_before_update(Request $request, $user_id,$item_id)
    {
// dd($action);
            // 未定義エラーを防ぐため、$userをnullで初期化
    $user = null;
   

        if (Auth::check()) {
        $user = Auth::user();


        $user->update($request->only('post_number', 'address', 'building'));
        }

        $item = Item::findOrFail($item_id);

        return view('item_buy',compact('item','user','item_id','user_id'));
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

// $image_path2 = session('image_path2');
// dd($request);
        $user = Auth::user();
            // `$user`変数が存在しない場合（ログインしていない場合）のエラーハンドリング
    if (!$user) {
        return redirect()->route('login')->with('error', 'ログインしてください。');
    }
        $user->user_image = $request->input('user_image');
        // $user ->user_image->$image_path2;
        $user->update($request->only('name', 'post_number', 'address', 'building','user_image'));
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
    return redirect()->back()->with('success', '商品画像アップロードできました！')->with('image_path', 'storage/' .$img);
}

public function user_image_upload(Request $request){
    
    //  $img=$request->imgpath;  //formで設置したname名
     $filename=$request->user_image->getClientOriginalName();
// dd($filename);
    $img=$request->user_image->storeAs('public/user_images',$filename);



    $img = str_replace('public/', '', $img);
    //  dd($img);
    $user = Auth::user();
    $user->update(['first_time_access' => 0]);
    return redirect()->route('profile_edit')->with('success', 'ユーザイメージアップロードしました。')->with('image_path2', 'storage/' .$img);
}




public function thanks_sell_create(Request $request)
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

  return redirect('/')->with('success', '商品を出品しました。');
}

// public function thanks_sell_create(Request $request)
// {
//     $item = $request->only(['name','price','brand','explain','condition','category','item_image']);

//   $item['user_id'] = auth()->id();

//   Item::create($item);

//   return redirect('/')->with('success', '商品を登録しました。');
// }


public function thanks_buy_create(Request $request)
{
// dd($request);
//     $order = $request->only(['payment']);
//   $order['user_id'] = auth()->id();
//   $order['item_id'] = Item::findOrFail($item_id);

    //   $validated = $request->validate([
    //     'payment' => 'required|string|max:255',
    //     'item_id' => 'required|integer|exists:items,id', 
    // ]);

    // リクエストから必要なデータを直接取得する
    $paymentMethod = $request->input('payment');
    $itemId = $request->input('item_id');

    // ユーザーIDも取得
    $userId = auth()->id();

    // データベースに挿入するデータを整理
    $order = [
        'payment' => $paymentMethod,
        'user_id' => $userId,
        'item_id' => $itemId,
    ];

    //  $order = [
    //     'payment' => $validated['payment'],
    //     'user_id' => auth()->id(),
    //     'item_id' => $validated['item_id'],
    // ];


OrderHistory::create($order);

  return redirect('/')->with('success', '商品を購入しました。');
}

public function comment_create(Request $request)
{
// dd($request);


    // リクエストから必要なデータを直接取得する
    $paymentMethod = $request->input('comment');
    $itemId = $request->input('item_id');

    // ユーザーIDも取得
    $userId = auth()->id();

    // データベースに挿入するデータを整理
    $word = [
        'comment' => $paymentMethod,
        'user_id' => $userId,
        'item_id' => $itemId,
    ];



Comment::create($word);

return redirect()->back()->with('success', 'コメントが送信されました。');
}




}
