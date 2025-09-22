@extends('layouts.app')

@section('title', $item->name)

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endpush

@section('content')
<div class="item-detail-container">
    <div class="item-detail-left">
        <div class="item-image">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
        </div>
    </div>

    <div class="item-detail-right">
        <h2 class="item-name">{{ $item->name }}</h2>
        <p class="item-brand">{{ $item->brand_name }}</p>
        <p class="item-price">¥{{ number_format($item->price) }} <span>(税込)</span></p>

        <div class="item-actions">
            <span class="favorite">☆ {{ $item->favorites_count ?? 0 }}</span>
            <span class="comments">💬 {{ $item->comments->count() }}</span>
        </div>

        <!-- 下記は購入ページへのリンク -->
        <a href="{{ route('purchase.index', $item->id) }}" class="btn-purchase">購入手続きへ</a>

        <div class="item-description">
            <h3>商品説明</h3>
            <p>{!! nl2br(e($item->description)) !!}</p>
        </div>

        <div class="item-info">
            <h3>商品の情報</h3>
            <p>カテゴリー：
                @foreach($item->categories as $category)
                    <span class="category-tag">{{ $category->name }}</span>
                @endforeach
            </p>
            <p>商品の状態：{{ $item->itemCondition->name }}</p>
        </div>

        <div class="item-comments">
            <h3>コメント ({{ $item->comments->count() }})</h3>
            @foreach($item->comments as $comment)
                <div class="comment">
                    <span class="comment-user">{{ $comment->user->name }}</span>
                    <p class="comment-body">{{ $comment->body }}</p>
                </div>
            @endforeach

            <!-- コメント投稿フォーム 未実装 -->
            <form action="{{ route('items.show', $item->id) }}" method="post" class="comment-form">
                @csrf
                <textarea name="body" rows="3" placeholder="商品へのコメントを入力"></textarea>
                <button type="submit" class="btn-comment">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection
