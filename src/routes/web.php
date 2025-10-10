<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

// Stripe のリダイレクト URL
// ユーザーセッション情報は失われている可能性が高いため（別ドメイン遷移）
Route::controller(PurchaseController::class)->group(function () {
    Route::get('/purchase/success', 'success')->name('purchase.success'); // 支払い完了
    Route::get('/purchase/cancel',   'cancel')->name('purchase.cancel');  // 支払いキャンセル
});

// ログイン済み
Route::middleware(['auth'])->group(function () {
    // ログアウト処理
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});

// ログイン済みかつメール認証済み
Route::middleware(['auth', 'verified'])->group(function () {

    Route::controller(PurchaseController::class)->group(function () {
        Route::get ('purchase/{item_id}',         'index')        ->name('purchase.index');         // 購入画面
        Route::post('purchase/',                  'store')        ->name('purchase.store');         // 購入実行
        Route::get ('purchase/address/{item_id}', 'edit')         ->name('purchase.edit');          // 購入支払い先変更画面
        Route::put ('purchase/address/{item_id}', 'store_address')->name('purchase.store_address'); // 購入支払い先変更実行
    });

    Route::controller(MypageController::class)->group(function () {
        Route::get ('/mypage',               'index') ->name('mypage.index');  // マイページ
        Route::post('/mypage/profile/store', 'store') ->name('mypage.store');  // プロフィール作成
        Route::get ('/mypage/profile',       'edit')  ->name('mypage.edit');   // プロフィール設定
        Route::put ('/mypage/profile/{id}',  'update')->name('mypage.update'); // プロフィール更新
    });

    Route::controller(ItemController::class)->group(function () {
        Route::get ('/sell',            'sell')      ->name('sell');              // 出品画面
        Route::post('/sell',            'store')     ->name('sell.post');         // 出品実行
        Route::post('/items/{id}/like', 'toggleLike')->name('items.toggle-like'); // いいねの追加・削除
    });

    Route::controller(CommentController::class)->group(function () {
        Route::post('/items/comment/{item}', 'store')->name('comment.store');     // コメント保存
    });
});

// ゲスト専用ルート（ログイン・登録）
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get ('/register', 'create')->name('register');      // 登録フォーム表示
        Route::post('/register', 'store') ->name('register.post'); // 登録処理
        Route::get ('/login',    'login') ->name('login');         // ログインフォーム表示
        Route::post('/login',    'send')  ->name('login.post');    // ログイン処理
    });
});

// 共通でアクセスできるルート
Route::controller(ItemController::class)->group(function () {
    Route::get('/',           'index')->name('index');      // 商品一覧
    Route::get('/items/{id}', 'show') ->name('items.show'); // 商品詳細
});

// メール認証用ルート

// 認証メール送信後の画面（例：認証待ち）
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// メールのリンクをクリックしたとき
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('mypage.edit')
        ->with('success', 'メール認証が完了しました！');
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
