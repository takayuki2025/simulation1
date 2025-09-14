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


    // 認証済みかつメール認証済みのユーザーのみアクセス可能にしたいルート
    // handleOnetimeRedirectで認証をチェックするため、ここでは'verified'ミドルウェアを外します
    Route::get('/mypage/profile', [ItemController::class, 'profile_revise'])->middleware(['auth'])->name('profile_edit');





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






// その他のルート...

Route::patch('/profile_update', [ItemController::class, 'profile_update']);




Route::get('/mypage', [ItemController::class, 'profile_show'])->middleware(['auth'])->name('profile');
Route::get('/sell', [ItemController::class, 'item_sell_show'])->middleware(['auth'])->name('item_sell');

Route::get('/item/{item_id}', [ItemController::class, 'item_detail_show'])->name('item_detail');

Route::get('/purchase/{item_id?}', [ItemController::class, 'item_buy_show'])->name('item_buy');


// POSTルートの名前を'item.purchase.update'に変更し、パラメータの順序を修正
Route::post('/purchase/address/{item_id}/{user_id}', [ItemController::class, 'update'])->name('item.purchase.update');

// GETルートの名前を'item.purchase.edit'に変更し、パラメータの順序を修正
Route::get('/purchase/address/{item_id}/{user_id}', [ItemController::class, 'item_purchase_edit'])->name('item.purchase.edit');

Route::post('/upload', [ItemController::class, 'item_image_upload']);

Route::post('/upload2', [ItemController::class, 'user_image_upload']);
// Route::match(['get','post'],'/upload2', [ItemController::class, 'user_image_upload']);


Route::post('/thanks_sell', [ItemController::class, 'thanks_sell_create']);









Route::post('/comment_read', [ItemController::class, 'comment_create'])->name('comment_create');

Route::post('/items/{item}/favorite', [ItemController::class, 'favorite'])->name('item.favorite');





//購入処理（コンビニ払い完了処理まで/カード支払いstripe決済に繋げる処理）のルード
Route::post('/thanks_buy', [ItemController::class, 'thanks_buy_create'])->name('thanks_buy_create');


Route::get('/stripe_success', [ItemController::class, 'stripeSuccess'])->name('stripe_success');

// コンビニ/カード支払い共に処理完了後のページ移動のルード
Route::get('/thanks_buy', [ItemController::class, 'thanks_buy_show'])->name('thanks_buy');



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
