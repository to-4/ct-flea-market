<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    // テスト用画面
    Route::get('/', function () { return view('test'); })->name('test');;
    // ログアウト処理
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
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
