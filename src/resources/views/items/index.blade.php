@extends('layouts.app')

@section('title', '商品一覧')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush

@section('content')
<div class="items-container">
    <div class="items-tabs">
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
    </div>

    <div class="items-grid">
        @forelse($items as $item)
            <div class="item-card">
                <a href="{{ route('items.show', $item->id) }}" class="item-form">
                    <div class="item-image">
                        @if($item->purchase_count > 0)
                            <div class="soldout-ribbon">Sold</div>
                        @endif
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                </a>
            </div>
        @empty
            <p>マイリストに商品はありません</p>
        @endforelse
    </div>

    {{-- ページネーション --}}
    @if ($items->hasPages())
        <div class="pagination-container">
            {{ $items->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
