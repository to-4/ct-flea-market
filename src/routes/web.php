<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;

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

Route::middleware(['auth'])->group(function () {
    // ログアウト処理
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    // 商品一覧画面
    Route::get('/', [ItemController::class, 'index'])->name('index');
    // 商品詳細画面
    Route::post('/items/{id}', [ItemController::class, 'show'])->name('items.show');
    // 購入画面
    Route::get('purchase/{iitem_id}', [PurchaseController::class, 'index'])->name('purchase.index');
    // 購入実行
    Route::post('purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
});

Route::middleware('guest')->group(function () {
    // 登録フォーム表示
    Route::get('/register', [AuthController::class, 'create'])->name('register');
    // 登録処理
    Route::post('/register', [AuthController::class, 'store'])->name('register.post');
    // ログインフォーム表示
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    // ログイン処理
    Route::post('/login', [AuthController::class, 'send'])->name('login.post');
});
