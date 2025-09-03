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

Route::Patch('/', [ItemController::class, 'profile_update']);

Route::get('/mypage/profile', [ItemController::class, 'showOneTimePage'])
    ->middleware(['auth'])
    ->name('profile_edit');

Route::post('/mypage/profile', [ItemController::class, 'profile_revise'])->middleware(['auth'])->name('profile_edit');
Route::get('/mypage', [ItemController::class, 'profile_show'])->middleware(['auth'])->name('profile');
Route::get('/sell', [ItemController::class, 'item_sell_show'])->middleware(['auth'])->name('item_sell');

Route::get('/item/{id}', [ItemController::class, 'item_detail_show'])->name('item_detail');
Route::get('/purchase/{id}', [ItemController::class, 'item_buy_show'])->name('item_buy');
Route::get('/purchase/address/{id}', [ItemController::class, 'item_purchase_edit'])->name('address');

Route::post('/upload', [ItemController::class, 'item_image_upload']);

Route::post('/thanks_buy', [ItemController::class, 'thanks_buy_create']);
