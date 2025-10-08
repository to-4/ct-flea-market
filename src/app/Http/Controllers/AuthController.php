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

        // 認証リンクメール送信
        event(new Registered($user));

        // プロフィール設定へ
        // return redirect()->intended(route('mypage.edit'));

        // メール認証誘導画面
        return redirect()->route('verification.notice');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

}
