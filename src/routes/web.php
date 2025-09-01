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

Route::get('/', [ItemController::class, 'index']);
Route::get('/mypage/profile', [ItemController::class, 'profile_show']);

    // Route::get('/profile_edit', function () {
    //     if (session('one_time_access_granted')) {
    //         return Redirect::route('front_page'); // 既にアクセス済みの場合はリダイレクト
    //     }
    //     session(['one_time_access_granted' => true]); // 初回アクセス時のみフラグを設定
    //     return view('profile_edit');
    // })->name('profile_edit');

Route::Patch('/', [ItemController::class, 'profile_update']);


// 今回は使わないかも
// Route::middleware('auth')->group(function () {
//     Route::get('/', [ItemController::class, 'index']);
// });

Route::get('/mypage/profile', [ItemController::class, 'showOneTimePage'])
    ->middleware(['auth'])
    ->name('profile_edit');