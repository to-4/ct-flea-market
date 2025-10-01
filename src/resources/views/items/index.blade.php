@extends('layouts.app')

@section('title', '商品一覧')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush

@section('content')
<div class="items-container">
    <div class="items-tabs">
        <a href="{{ route('index') }}" class="tab-link {{ request()->routeIs('index') ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('index') }}" class="tab-link {{ request()->routeIs('index') ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="items-grid">
        @foreach($items as $item)
            <div class="item-card">
                <form action="{{ route('items.show', $item->id) }}" method="post" class="item-form">
                    @csrf
                    <button type="submit" class="item-button">
                        <div class="item-image">
                            @if($item->purchase_count > 0)
                                <div class="soldout-ribbon">Sold</div>
                            @endif
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                        </div>
                        <div class="item-name">{{ $item->name }}</div>
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
