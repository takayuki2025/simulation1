<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\BuyController;

use App\Http\Controllers\Auth\EmailVerificationController;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;



use Illuminate\Foundation\Auth\EmailVerificationRequest;

use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Auth\EmailVerificationController;

use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// フロントページ (認証不要)
Route::get('/', [ItemController::class, 'index'])->name('front_page');

// onetime.show ルートをauthミドルウェアの外に定義
Route::get('/onetime', [ItemController::class, 'handleOnetimeRedirect'])->name('onetime.show');

    // // メール認証通知ページ
    // Route::get('/email/verify', function () {
    //     return view('auth.verify-email');
    // })->name('verification.notice');

    // メール認証通知の再送信
    // Route::post('/email/verification-notification', function (Request $request) {
    //     $request->user()->sendEmailVerificationNotification();
    //     return back()->with('status', 'verification-link-sent');
    // })->middleware('throttle:6,1')->name('verification.send');


// // 認証済みかつメール認証済みのユーザーのみアクセス可能なルート
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/mypage/profile', [ItemController::class, 'editProfile'])->name('profile_edit');
// });

// Route::get('/onetime', [ItemController::class, 'handleOnetimeRedirect'])->name('onetime.show');

    // 認証済みかつメール認証済みのユーザーのみアクセス可能にしたいルート
    // handleOnetimeRedirectで認証をチェックするため、ここでは'verified'ミドルウェアを外します
    Route::get('/profile_edit', [ItemController::class, 'profile_revise'])->name('profile_edit');






// Fortifyが提供するデフォルトのログアウト処理
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// メール認証関連のルート
// メール認証通知ページを表示するルート
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

// メール認証リクエストを処理するルート
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// メール認証通知を再送信するルート
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
// Fortifyが提供するメール認証ルートを有効化するため、
// web.phpから以下のカスタムルート定義をすべて削除します。
// FortifyServiceProviderで設定した内容が自動的に適用されます。
// Route::get('/email/verify', ...
// Route::get('/email/verify/{id}/{hash}', ...
// Route::post('/email/verification-notification', ...
//     // ... その他の認証済みルート
// });





// その他のルート...
Route::get('/?tab=mylist', [ItemController::class, 'index']);
Route::get('/?tab=mylist', [ItemController::class, 'mylist_scour']);
Route::get('/item/search', [ItemController::class, 'scour']);
Route::patch('/', [ItemController::class, 'profile_update']);

Route::get('/mypage/profile/after', [ItemController::class, 'profile_revise'])
    ->middleware(['auth'])
    ->name('profile_edit2');




Route::get('/mypage', [ItemController::class, 'profile_show'])->middleware(['auth'])->name('profile');
Route::get('/sell', [ItemController::class, 'item_sell_show'])->middleware(['auth'])->name('item_sell');

Route::get('/item/{item_id}', [ItemController::class, 'item_detail_show'])->name('item_detail');

Route::get('/purchase/{item_id?}', [ItemController::class, 'item_buy_show'])->name('item_buy');

Route::post('/purchase/{user_id}/{item_id}', [ItemController::class, 'purchase_before_update'])->name('address_update');

Route::get('/purchase/address/{user_id}/{item_id}', [ItemController::class, 'item_purchase_edit'])->name('address');

Route::post('/upload', [ItemController::class, 'item_image_upload']);
Route::match(['get','post'],'/upload2', [ItemController::class, 'user_image_upload']);

Route::post('/thanks_sell', [ItemController::class, 'thanks_sell_create']);




Route::post('/thanks_buy', [ItemController::class, 'thanks_buy_create'])->name('buy_create');
Route::post('/thanks_buy', [ItemController::class, 'thanks_buy_create'])->name('thanks_buy_create');


Route::post('/comment_read', [ItemController::class, 'comment_create'])->name('comment_create');

Route::post('/items/{item}/favorite', [ItemController::class, 'favorite'])->name('item.favorite');



// 購入処理のルートを正しく修正
Route::post('create/purchase/', [BuyController::class, 'create'])->name('buy_create_stripe');
Route::get('/thanks_buy', [BuyController::class, 'thanks_buy_show'])->name('thanks_buy');


// Stripe決済ページへの新しいルート
Route::get('/stripe_payment/{item_id}', [BuyController::class, 'showStripePaymentForm'])->name('stripe_payment');
Route::get('/stripe_success', [ItemController::class, 'stripeSuccess'])->name('stripe_success');



// Route::get('/profile/first-time-setup', [ExhibitionController::class, 'showFirstTimeForm'])->name('first_time_profile');

// // 初回フォーム送信用のルート（バリデーションなし）
// Route::post('/profile/first-time-setup', [ExhibitionController::class, 'processFirstTimeProfile'])->name('process_first_time');

// // 2回目以降のプロフィール更新用ルート（バリデーションあり）
// Route::put('/profile/update', [ExhibitionController::class, 'profile_update'])->name('profile.update');

// mailhog受信テスト用
Route::get('/send-test-email', function () {
    try {
        Mail::raw('This is a test email from Laravel.', function (Message $message) {
            $message->to('test@example.com')->subject('Test Email');
        });
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Failed to send email: ' . $e->getMessage();
    }
});


// fortifyのコントローラが通常に機能しなかったので独自ルードを作りました。
// Route::post('/email/verification-notification', [ItemController::class, 'resendVerificationEmail'])
//     ->middleware(['auth', 'throttle:6,1'])
//     ->name('verification.send');