<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CommentController;


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

// ログイン済み
Route::middleware(['auth'])->group(function () {
    // ログアウト処理
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});

// ログイン済みかつメール認証済み
Route::middleware(['auth', 'verified'])->group(function () {
    // 購入画面
    Route::get('purchase/{item_id}', [PurchaseController::class, 'index'])->name('purchase.index');
    // 購入実行
    Route::post('purchase', [PurchaseController::class, 'store'])->name('purchase.store');
    // 購入支払い先変更画面
    Route::get('purchase/address/{item_id}', [PurchaseController::class, 'edit'])->name('purchase.edit');
    // 購入支払い先変更実行
    Route::put('purchase/address/{item_id}', [PurchaseController::class, 'store_address'])->name('purchase.store_address');
    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    // プロフィール新規作成
    Route::post('/mypage/profile/store', [MypageController::class, 'store'])->name('mypage.store');
    // マイページ（プロフィール設定）画面
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');
    // プロフィール更新
    Route::put('/mypage/profile/{id}', [MypageController::class, 'update'])->name('mypage.update');
    // 出品画面
    Route::get('/sell', [ItemController::class, 'sell'])->name('sell');
    // 出品実行
    Route::post('/sell', [ItemController::class, 'store'])->name('sell.post');
    // いいねの追加・削除
    Route::post('/items/{id}/like', [ItemController::class, 'toggleLike'])->name('items.toggle-like');

    // コメント保存
    Route::post('/items/comment/{item}', [CommentController::class, 'store'])->name('comment.store');
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

// 共通でアクセスできるルート
Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

// メール認証用ルート
// 認証メール送信後の画面（例：認証待ち）
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// メールのリンクをクリックしたとき
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('mypage.edit')
                     ->with('success', 'メール認証が完了しました！');;
})->middleware(['auth', 'signed'])->name('verification.verify');

// 再送用
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', '認証メールを再送しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// メール認証画面
Route::get('/email/verify-code', function () {
    return view('auth.verify-code');
})->name('verification.code.notice');

// 認証コード検証
Route::post('/email/verify-code', [AuthController::class, 'verifyCode'])
    ->name('verification.code.check');

// 認証コード再送用
Route::post('/email/verification-code/resend', function () {
    $user = Auth::user();

    if (! $user) {
        abort(403);
    }

    // 6桁の新しい認証コードを生成
    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // DBに保存（期限は10分）
    $user->update([
        'email_verification_code' => $code,
        'email_verification_expires_at' => now()->addMinutes(10),
    ]);

    // メール送信（MailHog で確認可能）
    Mail::raw("新しい認証コード: {$code}\n\n有効期限: 10分", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('【Flea Market】メール認証コード再送');
    });

    return back()->with('success', '新しい認証コードを送信しました。');
})->middleware(['auth', 'throttle:3,10'])->name('verification.code.resend');