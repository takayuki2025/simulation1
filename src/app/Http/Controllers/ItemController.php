<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileImageRequest;

use App\Models\Item;
use App\Models\User;
use App\Models\OrderHistory;
use App\Models\Comment;
use App\Models\Good;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\PurchaseRequest;

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


                // --- ここから追加 ---
        // 取得した商品コレクションをループ処理
        $items->each(function ($item) {
            // remainが0の場合、priceの値をsoldに設定
            if ($item->remain == 0) {
                $item->price = 'sold';
            }
        });
        // --- ここまで追加 ---

        return view('front_page',compact('items', 'tab'));
    }


    public function scour(Request $request)
{
        $item_search = $request->input('all_item_search');

        $items = Item::ItemSearch($item_search)->get();


        return view('front_page', compact('items'));
}




    public function mylist_scour(Request $request)
    {
        // URLのGETパラメータ'tab'を取得。デフォルトは'all'
        $tab = $request->query('tab', 'all');
        $item_search = $request->input('all_item_search');

        if ($tab === 'mylist') {
            // 'mylist'タブの場合、いいねした商品を取得
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'ログインしてください。');
            }

            // いいねした商品のIDリストを取得
            $likedItemIds = Good::where('user_id', $user->id)->pluck('item_id');

            // Itemモデルから、いいねした商品のIDを検索対象として取得
            $query = Item::whereIn('id', $likedItemIds);

            // 検索キーワードがある場合、さらに絞り込み
            if ($item_search) {
                $query->ItemSearch($item_search);
            }

            $items = $query->get();

        } else {
            // 'all'タブの場合、全商品から検索
            $query = Item::query();
            
            // 検索キーワードがある場合、絞り込み
            if ($item_search) {
                $query->ItemSearch($item_search);
            }

            $items = $query->get();
        }

                // --- ここから追加 ---
        // 取得した商品コレクションをループ処理
        $items->each(function ($item) {
            // remainが0の場合、priceの値をsoldに設定
            if ($item->remain == 0) {
                $item->price = 'sold';
            }
        });
        // --- ここまで追加 ---


        return view('front_page', compact('items', 'tab'));
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

                // --- ここから追加 ---
        // remainが0の場合、priceを'sold'という文字列に変更
        if ($item->remain == 0) {
            $item->price = 'sold';
        }
        // --- ここまで追加 ---



            $item_id = $item->id;
            $comments = Comment::where('item_id',$item_id)->get();

                // ログイン中のユーザー情報を取得
                    $user = Auth::user();
                    $isFavorited = false; // デフォルト値を`false`に設定
                $favoritesCount = Good::where('item_id', $item->id)->count();

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
            return view('item_detail',compact('item' ,'item_id','comments', 'isFavorited','favoritesCount','user'));
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

        return view('address',compact('user','item_id','user_id','item'));
    }


        public function purchase_before_update(AddressRequest $request, $user_id,$item_id)
    {
            // 未定義エラーを防ぐため、$userをnullで初期化
            $user = null;


            if (Auth::check()) {
                $user = Auth::user();
                $user->update($request->only('post_number', 'address', 'building'));
            }

            $item = Item::findOrFail($item_id);

       return redirect()->route('item_buy', ['item_id' => $item_id])
                         ->with('success', '住所情報を更新しました。');
        // return view('item_buy',compact('item','user','item_id','user_id'));
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
                    // `$user`変数が存在しない場合（ログインしていない場合）のエラーハンドリング
                    if (!$user) {
                return redirect()->route('login')->with('error', 'ログインしてください。');
                }

            $user->user_image = $request->input('user_image');
            $user->update($request->only('name', 'post_number', 'address', 'building',));
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
            $items = Item::all();
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
        return view('profile_edit', compact('user','items'));
    }


        public function item_image_upload(Request $request){

                    $rules = [
            'item_image' => 'required|mimes:jpeg,png' ,
        ];

                    $messages = [
            'item_image.required' => '商品画像ファイルをアップロードしてください。',
            'item_image.mimes' => '商品画像ファイルは.jpegまたは.png形式でアップロードしてください。',
        ];

                    $validator = Validator::make($request->all(), $rules, $messages);

                            if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

            //  $img=$request->imgpath;  //formで設置したname名
            $filename=$request->item_image->getClientOriginalName();
            $img=$request->item_image->storeAs('public/item_images',$filename);
            $img = str_replace('public/', '', $img);

        return redirect()->back()->with('success', '商品画像アップロードできました！')->with('image_path', 'storage/' .$img);
    }


    public function user_image_upload(ProfileImageRequest $request)
    {

        // アップロードされたファイルが存在するか、かつ有効なファイルかを確認
        if ($request->hasFile('user_image') && $request->file('user_image')->isValid()) {
            $filename = $request->user_image->getClientOriginalName();
            $path = $request->user_image->storeAs('public/user_images', $filename);

            // パスから 'public/' を除去してデータベースに保存する形式に変換
            $dbPath = str_replace('public/', '', $path);

            // アップデート処理
            $user = Auth::user();
            $user->update([
                'first_time_access' => 0,
                'user_image' => 'storage/' . $dbPath // データベースに保存
            ]);

            
            return redirect()->route('profile_edit')->with('success', 'ユーザイメージをアップロードしました。')->with('image_path2', 'storage/' .$dbPath);
        }

        // ファイルが存在しない、または無効な場合
        return back()->with('error', '画像ファイルがありません。');
    }


        public function thanks_sell_create(ExhibitionRequest $request)
    {
// dd($request);
            $item = $request->only(['name','price','brand','explain','condition','category','item_image']);

//  $selectedCategories = $request->input('category');

            $item['user_id'] = auth()->id();

            // --- ここから追加 ---
            // remainカラムに任意の値を指定（例：1）
            $item['remain'] = 1;
            // --- ここまで追加 ---

                    // 4. カテゴリーデータを $item 配列に追加
        // categoriesというキーで、選択されたカテゴリーを代入
        // $item['category'] = $selectedCategories;


            Item::create($item);

        return redirect('/')->with('success', '商品を出品しました。');
    }


        public function thanks_buy_create(PurchaseRequest $request)
{
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

        OrderHistory::create($order);

                    // --- ここから追加 ---
        // 購入された商品を取得
        $item = Item::findOrFail($itemId);

        // remainカラムを1減らす（デクリメント）
        // ゼロを下回らないように保護
        if ($item->remain > 0) {
            $item->remain = $item->remain - 1;
        } else {
            // 在庫がない場合の処理（例：エラーメッセージを表示）
            return redirect('/')->with('error', 'この商品は在庫がありません。');
        }

        // 変更をデータベースに保存
        $item->save();
        // --- ここまで追加 ---








        return redirect('/')->with('success', '商品を購入しました。');
    }


        public function comment_create(CommentRequest $request)
    {
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
