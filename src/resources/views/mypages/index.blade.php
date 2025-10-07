@extends('layouts.app')

@section('title', 'マイページ')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/mypages/index.css') }}">
@endpush

@section('content')
<div class="mypage-container">
    {{-- プロフィール情報 --}}
    <div class="profile-section">
        <div class="profile-image">
            <img src="{{ $profile?->image_url ?: asset('images/no-image.png') }}" alt="プロフィール画像">
        </div>
        <div class="profile-info">
            <h2 class="profile-name">{{ $profile?->display_name ?: $user->name }}</h2>
            <a href="{{ route('mypage.edit') }}" class="btn-edit">プロフィールを編集</a>
        </div>
    </div>

    {{-- タブ --}}
    <div class="mypage-tabs">
        <a href="/mypage?page=sell" class="tab-link {{ request('page') === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="/mypage?page=buy" class="tab-link {{ request('page') === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    {{-- 商品一覧 --}}
    <div class="items-grid">
        @forelse($items as $item)
            <div class="item-card">
                <a href="{{ route('items.show', $item->id) }}" class="item-form">
                    <div class="item-image">
                        @if($item->purchase_count > 0)
                            <div class="soldout-ribbon">Sold</div>
                        @endif
                        <img src="{{ $item?->image_url ?: asset('images/no-image.png') }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                </a>
            </div>
        @empty
            <p class="no-items">商品がありません</p>
        @endforelse
    </div>
</div>
@endsection
