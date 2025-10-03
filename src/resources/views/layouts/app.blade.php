<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH フリマ')</title>
    {{-- 共通 --}}
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @stack('page-css')   {{-- ページ側のCSS --}}
</head>
<body>
    <header class="header">
        <div class="header__logo">
            <a href="{{ route('index') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH ロゴ" class="logo-img">
            </a>
        </div>
        @if (!Route::is('register', 'login'))
        <div class="header__search">
            <form action="{{ route('index') }}" method="GET">
                <input type="text" name="keyword" placeholder="なにをお探しですか？"
                        value="{{ request('keyword') }}">
            </form>
        </div>
        <nav class="header__nav">
            <ul>
                <li>
                    @if (Auth::check())
                    <form class="form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="header__link">ログアウト</button>
                    </form>
                    @else
                        <a href="{{ route('login') }}" class="header__link">ログイン</a>
                    @endif
                </li>
                <li><a href="{{ route('mypage.index', ['page' => 'sell']) }}">マイページ</a></li>
                <li><a href="{{ route('sell') }}" class="btn-exhibit">出品</a></li>
            </ul>
        </nav>
        @endif
    </header>

    <main class="main-content">

        {{-- フラッシュメッセージ（任意） --}}
        @if(session('status'))
            <div class="flash flash-success">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>

    @stack('page-js') {{-- ページ専用JSはここ --}}
</body>
</html>
