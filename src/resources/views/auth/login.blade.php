@extends('layouts.app')

@section('title', 'ログイン')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('content')
<div class="login-container">
    <h2 class="login-title">ログイン</h2>

    <form method="POST" action="{{ route('login.post') }}" class="login-form" novalidate>
        @csrf

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" required
                    value = "{{ old('email') }}">
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn-login">ログインする</button>
        </div>
    </form>

    <div class="login-link">
        <a href="/register">会員登録はこちら</a>
    </div>
</div>
@endsection
