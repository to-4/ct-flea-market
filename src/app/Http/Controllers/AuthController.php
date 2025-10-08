<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function login()
    {
        return view('auth.login');
    }

    public function send(LoginRequest $request)
    {
        $credentials = $request->validated();

        // remember チェックボックスがあれば第二引数で制御
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()) {

                // 認証コードを生成（6桁・ゼロ埋め）
                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                // コードと有効期限を保存（例: 10分間）
                $user->update([
                    'email_verification_code' => $code,
                    'email_verification_expires_at' => now()->addMinutes(10),
                ]);

                // メール送信（MailHogで確認可）
                Mail::raw("以下の6桁コードを入力して認証してください：\n\n{$code}\n\n有効期限：10分", function ($message) use ($user) {
                    $message->from('no-reply@example.com', 'Flea Market 運営');
                    $message->to($user->email)
                            ->subject('【Flea Market】メール認証コード');
                });

                // 一旦ログインは成立させつつ、認証誘導ページへリダイレクト
                return redirect()->route('verification.notice');
            }

            return redirect()->intended(route('index')); // 成功 → 管理画面へ
        }

        // ここでは「認証失敗」を email フィールドのエラーとして返す（項目下に出せる）
        throw ValidationException::withMessages([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(StoreUserRequest $request)
    {
        // 検証はここへ来る前に完了（$request->validated() でOK）
        $data = $request->validated();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            // 必要なら管理画面用フラグやロール付与など
            // 'is_admin' => true,
        ]);

        // そのままログインさせたい場合
        Auth::login($user);

        // 認証コードを生成（6桁・ゼロ埋め）
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // コードと有効期限を保存（例: 10分間）
        $user->update([
            'email_verification_code' => $code,
            'email_verification_expires_at' => now()->addMinutes(10),
        ]);

        // メール送信（MailHogで確認可）
        Mail::raw("以下の6桁コードを入力して認証してください：\n\n{$code}\n\n有効期限：10分", function ($message) use ($user) {
            $message->from('no-reply@example.com', 'Flea Market 運営');
            $message->to($user->email)
                    ->subject('【Flea Market】メール認証コード');
        });

        // メール認証誘導画面
        return redirect()->route('verification.notice');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $user = Auth::user();

        if (! $user ||
            $user->email_verification_code !== $request->code ||
            $user->email_verification_expires_at->isPast()) {
            return back()->withErrors(['code' => '認証コードが無効か、期限切れです。']);
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_expires_at' => null,
        ])->save();

        return redirect()->route('mypage.edit')->with('success', 'メール認証が完了しました！');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

}
