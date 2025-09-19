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
                <div class="item-image">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                </div>
                <div class="item-name">{{ $item->name }}</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
