@extends('layouts.app')

@section('title', '商品一覧')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush

@section('content')
<div class="items-container">
    <div class="items-tabs">
        <!-- ↓ 20251002 ↓ -->
        <!-- <a href="{{ route('index') }}" class="tab-link {{ request()->routeIs('index') ? 'active' : '' }}">
            おすすめ</a>
        <a href="{{ route('index') }}" class="tab-link {{ request()->routeIs('index') ? 'active' : '' }}">マイリスト</a> -->
        <a href="{{ route('index') }}"
            class="tab-link {{ request('tab') !== 'mylist' ? 'active' : '' }}">
            おすすめ
        </a>

        @auth
            {{-- ログインしている場合は有効なリンク --}}
            <a href="{{ route('index', ['tab' => 'mylist']) }}"
            class="tab-link {{ request('tab') === 'mylist' ? 'active' : '' }}">
            マイリスト
            </a>
        @else
            {{-- ログインしていない場合はリンクを無効化 --}}
            <span class="tab-link disabled">マイリスト</span>
        @endauth
        <!-- ↑ 20251002 ↑ -->
    </div>

    <div class="items-grid">
        {{-- @foreach($items as $item) --}} <!-- 20251002 -->
        @forelse($items as $item) <!-- 20251002 -->
            <div class="item-card">
            <!-- 20251002 -->
            <!-- <form action="{{ route('items.show', $item->id) }}" method="post" class="item-form"> -->
                 <!-- @csrf
                 <button type="submit" class="item-button"> -->
                <a href="{{ route('items.show', $item->id) }}" class="item-form"> <!-- 20251002 GETに変更 -->
            <!-- 20251002 -->
                    <div class="item-image">
                        @if($item->purchase_count > 0)
                            <div class="soldout-ribbon">Sold</div>
                        @endif
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                    <!-- </button> --> <!-- 20251002 -->
                    <!-- </form> --> <!-- 20251002 -->
                </a> <!-- 20251002 -->
            </div>
        {{-- @endforeach --}} <!-- 20251002 -->
        @empty <!-- 20251002 -->
            <p>マイリストに商品はありません</p> <!-- 20251002 -->
        @endforelse <!-- 20251002 -->
    </div>
</div>
@endsection
