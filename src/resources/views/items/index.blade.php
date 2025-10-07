@extends('layouts.app')

@section('title', '商品一覧')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush

@section('content')
<div class="items-container">
    <div class="items-tabs">
        <a href="{{ route('index', array_merge(request()->only('keyword'), ['tab' => null])) }}"
            class="tab-link {{ request('tab') !== 'mylist' ? 'active' : '' }}">
            おすすめ
        </a>
        <a href="{{ route('index', array_merge(request()->only('keyword'), ['tab' => 'mylist'])) }}"
            class="tab-link {{ request('tab') === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    @if ($items->isEmpty())
        <p class="no-items-message">表示する商品がありません。</p>
    @else
        <div class="items-grid">
            @foreach($items as $item)
                <div class="item-card">
                    <a href="{{ route('items.show', $item->id) }}" class="item-form">
                        <div class="item-image">
                            @if($item->purchase_count > 0)
                                <div class="soldout-ribbon">Sold</div>
                            @endif
                            <img src="{{ $item->image_url ?: asset('images/no-image.png') }}" alt="{{ $item->name }}">
                        </div>
                        <div class="item-name">{{ $item->name }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
    
    {{-- ページネーション --}}
    @if ($items->hasPages())
        <div class="pagination-container">
            {{ $items->appends(request()->only('keyword', 'tab'))->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
