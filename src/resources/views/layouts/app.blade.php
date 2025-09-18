<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH フリマ')</title>
    {{-- 共通 --}}
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
</head>
<body>
    <header class="header">
        <div class="header__logo">
            <a href="/">COACHTECH</a>
        </div>
        <div class="header__search">
            <input type="text" placeholder="なにをお探しですか？">
        </div>
        <nav class="header__nav">
            <ul>
                <li><a href="{{ route('logout') }}">ログアウト</a></li>
                <li><a href="/">マイページ</a></li>
                <li><a href="/" class="btn-exhibit">出品</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
