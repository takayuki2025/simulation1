<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;


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

Route::get('/?tab=mylist', [ItemController::class, 'index']);

Route::get('/item/search', [ItemController::class, 'scour']);

Route::Patch('/', [ItemController::class, 'profile_update']);

Route::get('/mypage/profile', [ItemController::class, 'showOneTimePage'])
    ->middleware(['auth'])
    ->name('profile_edit');

Route::post('/mypage/profile', [ItemController::class, 'profile_revise'])->middleware(['auth'])->name('profile_edit');
Route::get('/mypage', [ItemController::class, 'profile_show'])->middleware(['auth'])->name('profile');
Route::get('/sell', [ItemController::class, 'item_sell_show'])->middleware(['auth'])->name('item_sell');

Route::get('/item/{item_id}', [ItemController::class, 'item_detail_show'])->name('item_detail');

Route::get('/purchase/{item_id?}', [ItemController::class, 'item_buy_show'])->name('item_buy');

Route::Patch('/purchase/{user_id}/{item_id}', [ItemController::class, 'purchase_before_update'])->name('address_update');

Route::get('/purchase/address/{user_id}/{item_id}', [ItemController::class, 'item_purchase_edit'])->name('address');

Route::post('/upload', [ItemController::class, 'item_image_upload']);
Route::match(['get','post'],'/upload2', [ItemController::class, 'user_image_upload']);

Route::post('/thanks_sell', [ItemController::class, 'thanks_sell_create']);
Route::post('/thanks_buy', [ItemController::class, 'thanks_buy_create'])->name('buy_create');

Route::post('/comment_read', [ItemController::class, 'comment_create'])->name('comment_create');

Route::post('/items/{item}/favorite', [ItemController::class, 'favorite'])->name('item.favorite');
