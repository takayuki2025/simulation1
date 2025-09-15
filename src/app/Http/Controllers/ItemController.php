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

use Stripe\Stripe;
use Stripe\Checkout\Session;


use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    // use VerifiesEmails;
use Illuminate\Support\Str;

class ItemController extends Controller
{

// フロントページを表示し、持続検索機能とタブの切り替えを処理します。
    public function index(Request $request)
    {
        // URLのGETパラメータ'tab'を取得。デフォルトは'all'
        $tab = $request->query('tab', 'all');

        // URLのGETパラメータ'all_item_search'を取得
        $searchQuery = $request->query('all_item_search');

        if ($tab === 'mylist') {
            // 'mylist'タブの場合、いいねした商品を取得
            $user = Auth::user();
            if (!$user) {
                $items = collect([]); // 未認証ユーザーの場合、空のコレクションを渡す
            } else {
                // Goodモデルを介して関連するItemを取得
                $items = Good::where('user_id', $user->id)->with('item')->get()->map(function ($good) {
                    return $good->item;
                });
            }

            // 取得したコレクションを検索キーワードでフィルタリング
            if (!empty($searchQuery)) {
                $items = $items->filter(function ($item) use ($searchQuery) {
                    return stripos($item->name, $searchQuery) !== false;
                });
            }
        } else {
            // 'all'タブ（またはデフォルト）の場合、出品者自身の商品を除いて全商品を取得
            $query = Item::query();
            // Auth::id()がnullでないことを確認してからwhere句を適用
            if (Auth::id()) {
                $query->where('user_id', '!=', Auth::id());
            }

            // 検索キーワードがあれば、クエリをフィルタリング
            if (!empty($searchQuery)) {
                $query->where('name', 'like', '%' . $searchQuery . '%');
            }
            $items = $query->get();
        }

        // 取得した商品コレクションをループ処理
        $items->each(function ($item) {
            // remainが0の場合、priceの値をsoldに設定
            if ($item->remain == 0) {
                $item->price = 'sold';
            }
        });

        return view('front_page', compact('items', 'tab'));
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


        public function item_purchase_edit($item_id,$user_id)
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



        public function item_sell_show(Request $request)
    {
            if (Auth::check()) {
            $items = Item::all();
            }
            return view('item_sell',compact('items'));
    }



    public function profile_show(Request $request)
    {
        $user = Auth::user();

        // ログイン状態を確認
        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください。');
        }

        // URLのGETパラメータ'page'を取得。デフォルトは'sell'
        $page = $request->input('page', 'sell');
        $items = collect();

        // pageの値に応じてデータを取得
        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($page === 'buy') {
            $items = OrderHistory::where('user_id', $user->id)->with('item')->get();
        }
        return view('profile', compact('user', 'items', 'page'));
    }



///onetime'へのアクセスを処理し、認証状態に応じてリダイレクトする
    public function handleOnetimeRedirect(): RedirectResponse
    {
        // ユーザーが認証済みかどうかを確認
        if (Auth::check()) {
            $user = Auth::user();

            // メール認証が完了しているか確認
            if ($user->hasVerifiedEmail()) {
                // メール認証済みの場合、'front_page'ルートへリダイレクト
                return redirect()->route('front_page');
            }

            // ユーザーは認証済みだが、メールが未認証の場合
            // Fortifyの認証メール再送信ページへリダイレクト
            return redirect()->route('verification.notice');
        }

        // ユーザーが未認証の場合、Fortifyのログインページへリダイレクト
        return redirect()->route('login');
    }



// ユーザー関係の処理

        public function profile_revise(Request $request)
    {
        // dd($request);
        if (Auth::check()) {
        $user = Auth::user();
        }
        return view('profile_edit',compact('user'));
    }


        public function profile_update(ProfileRequest $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (!$user) {
                // セキュリティ上のチェック
                return redirect()->route('login')->with('error', 'ログインしてください。');
            }

            // first_time_accessをtrueに設定するための更新
            $updateData = $request->only('name', 'post_number', 'address', 'building');
            $updateData['first_time_access'] = true;

            $user->user_image = $request->input('user_image');
            $user->update($updateData);

            // update()の後の$userオブジェクトは、更新された最新の状態を反映しています。
        }
        return redirect()->route('front_page')->with('success', 'プロフィールを更新しました');
    }


        public function update(AddressRequest $request, $itemId, $userId)
    {
        // ユーザーIDを使ってユーザーを取得します。
        $user = User::find($userId);

        // ユーザーが存在しない場合はエラーを返します。
        if (!$user) {
            return redirect()->back()->with('error', 'ユーザーが見つかりません。');
        }

        // リクエストから新しい住所情報を取得してユーザーを更新します。
        // AddressRequestでバリデーション済みのため、直接アクセスします。
        $user->update([
            'post_number' => $request->post_number,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        // 住所更新後に購入処理へリダイレクト
        return redirect()->route('item_buy', ['item_id' => $itemId]);
    }


    // プロフィール編集画面からユーザーイメージのアップロード処理
    public function user_image_upload(ProfileImageRequest $request)
    {
        // アップロードされたファイルが存在するか、かつ有効なファイルかを確認
        if ($request->hasFile('user_image') && $request->file('user_image')->isValid()) {
            // ランダムなファイル名を作成（user_image_ + ランダムな文字列 + 元の拡張子）
            $originalName = $request->user_image->getClientOriginalName();
            $extension = $request->user_image->getClientOriginalExtension();
            $randomName = 'user_image_' . Str::random(30) . '.' . $extension;

            // `storeAs`メソッドで指定したファイル名で保存
            // 'public'ディスクを使用
            $path = $request->user_image->storeAs('public/user_images', $randomName);

            // データベースに保存するパスを生成
            // 'public/' プレフィックスを削除
            $dbPath = str_replace('public/', '', $path);

            // アップデート処理
            $user = Auth::user();
            $user->update([
                'first_time_access' => 0,
                'user_image' => 'storage/' . $dbPath // データベースにはstorage/からのパスを保存
            ]);

            return redirect()->route('profile_edit')->with('success', 'ユーザーイメージをアップロードしました。')->with('image_path2', 'storage/' . $dbPath);
        }

        // ファイルが存在しない、または無効な場合
        return back()->with('error', '画像ファイルがありません。');
    }



// 購入商品・出品商品関係の処理

        public function thanks_buy_create(Request $request)
    {
        $item = Item::find($request->item_id);

        $rules = [
            'payment' => 'required',
            'address' => 'required',
        ];
        $messages = [
            'payment.required' => '支払い方法を選択してください。',
            'address.required' => '配送先住所が入力されていません。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($item->remain < 1) {
            $validator->errors()->add('item_id', 'この商品は在庫がありません。');
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->input('payment') === 'コンビニ払い') {
            // 認証済みユーザーの情報を取得
            $user = Auth::user();

            // ユーザー情報をまとめて一つの文字列としてbuy_addressカラムに保存
            $buyAddress = "{$user->name}\n{$user->post_number}\n{$user->address}\n{$user->building}";

            OrderHistory::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'status' => '購入済み',
                'buy_address' => $buyAddress,
                'payment' => 'コンビニ払い'
            ]);

            $item->decrement('remain');

            return redirect()->route('thanks_buy');

        } elseif ($request->input('payment') === 'カード支払い') {

            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe_success', [
                    'item_id' => $item->id, 
                    'address' => $request->address, 
                    'payment' => 'カード支払い'
                ]),
                'cancel_url' => route('item_buy', ['item_id' => $item->id]),
            ]);

            return redirect($session->url, 303);
        }
    }


        public function thanks_sell_create(ExhibitionRequest $request)
    {
        // リクエストから必要なデータを取得
        $item = $request->only([
            'name',
            'price',
            'brand',
            'explain',
            'condition',
            'item_image',
        ]);

        // カテゴリーデータを明示的に取得し、JSON形式に変換
        $selectedCategories = $request->input('category');
        $item['category'] = json_encode($selectedCategories);

        // ログインユーザーIDとremainを付与
        $item['user_id'] = auth()->id();
        $item['remain'] = 1;

        // データベースに商品を保存
        Item::create($item);

        // 商品出品後のリダイレクトと成功メッセージの設定
        return view('thanks_sell');
    }


    //  出品商品のイメージのアップロード
    public function item_image_upload(Request $request)
    {
        // バリデーションルール
        $rules = [
            'item_image' => 'required|mimes:jpeg,png',
        ];

        // バリデーションメッセージ
        $messages = [
            'item_image.required' => '商品画像ファイルをアップロードしてください。',
            'item_image.mimes' => '商品画像ファイルは.jpegまたは.png形式でアップロードしてください。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // バリデーション失敗時の処理
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ファイル名にランダムな文字列を付与
        $extension = $request->file('item_image')->getClientOriginalExtension();
        $randomName = 'item_image_' . Str::random(30) . '.' . $extension;

        // 画像を保存
        $path = $request->item_image->storeAs('public/item_images', $randomName);
        $dbPath = str_replace('public/', '', $path);

        return redirect()->back()->with('success', '商品画像アップロードできました！')->with('image_path', 'storage/' . $dbPath);
    }



// いいね・コメント機能関係

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


        public function comment_create(CommentRequest $request)
    {
        // リクエストから必要なデータを取得
        $comment = $request->input('comment');
        $itemId = $request->input('item_id');
        $userId = auth()->id();

        // データベースに挿入するデータを整理
        $word = [
            'comment' => $comment,
            'user_id' => $userId,
            'item_id' => $itemId,
        ];

        // コメントを保存
        Comment::create($word);

        // 明示的に商品詳細ページにリダイレクト
        return redirect()->route('item_detail', ['item_id' => $itemId])->with('success', 'コメントが送信されました。');
    }



// stripe・共通購入完了画面処理

    public function stripeSuccess(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

            // 認証済みユーザーの情報を取得
            $user = Auth::user();

            // ユーザー情報をまとめて一つの文字列としてbuy_addressカラムに保存
            $buyAddress = "{$user->name}\n{$user->post_number}\n{$user->address}\n{$user->building}";

        OrderHistory::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            // 'status' => '購入済み',
            'buy_address' => $buyAddress,
            'payment' => 'カード支払い'
        ]);

        $item = Item::find($request->item_id);
        $item->decrement('remain');

        return redirect()->route('thanks_buy');
    }


    // 決済完了ページを表示する
    public function thanks_buy_show()
    {
        return view('thanks_buy');
    }

}
