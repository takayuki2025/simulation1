<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [ItemController::class, 'index'])->name('front_page');

Route::get('/onetime', [ItemController::class, 'handleOnetimeRedirect'])->name('onetime.show');

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

// Fortifyが提供するデフォルトのログアウト処理
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


Route::get('/item/{item_id}', [ItemController::class, 'item_detail_show'])->name('item_detail');

Route::get('/purchase/{item_id}', [ItemController::class, 'item_buy_show'])->name('item_buy');

Route::patch('/purchase/address/{item_id}/{user_id?}', [ItemController::class, 'update'])->name('item.purchase.update');
Route::get('/purchase/address/{item_id}/{user_id?}', [ItemController::class, 'item_purchase_edit'])->name('item.purchase.edit');

Route::get('/sell', [ItemController::class, 'item_sell_show'])->middleware(['auth'])->name('item_sell');

Route::get('/mypage', [ItemController::class, 'profile_show'])->middleware(['auth'])->name('profile');

Route::get('/mypage/profile', [ItemController::class, 'profile_revise'])->middleware(['auth'])->name('profile_edit');

Route::post('/thanks_sell', [ItemController::class, 'thanks_sell_create']);
Route::get('/thanks_sell', [ItemController::class, 'thanks_sell_create']);

//購入処理（コンビニ払い完了処理まで/カード支払いstripe決済に繋げる処理）のルード
Route::post('/thanks_buy', [ItemController::class, 'thanks_buy_create'])->name('thanks_buy_create');
// カード支払いでの処理
Route::get('/stripe_success', [ItemController::class, 'stripeSuccess'])->name('stripe_success');
// コンビニ/カード支払い共に処理完了後のページ移動のルード
Route::get('/thanks_buy', [ItemController::class, 'thanks_buy_show'])->name('thanks_buy');


Route::patch('/profile_update', [ItemController::class, 'profile_update']);

Route::post('/upload2', [ItemController::class, 'user_image_upload']);

Route::post('/upload', [ItemController::class, 'item_image_upload']);

Route::post('/comment_read', [ItemController::class, 'comment_create'])->name('comment_create');

Route::post('/items/{item}/favorite', [ItemController::class, 'favorite'])->name('item.favorite');


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
