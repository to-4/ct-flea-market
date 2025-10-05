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
        <p class="item-brand">{{ $item->brand_name ?? 'ブランド名未登録'}}</p>
        <p class="item-price">¥{{ number_format($item->price) }} <span>(税込)</span></p>

        <div class="item-actions">
            {{-- いいね --}}
            <form action="{{ route('items.toggle-like', $item->id) }}" method="post" class="like-form">
                @csrf
                <button type="submit" class="action like-button {{ $item->likes->contains('user_id', Auth::id()) ? 'liked' : '' }}">
                    <span class="icon">☆</span>
                    <span class="count">{{ $item->likes->count() }}</span>
                </button>
            </form>

            {{-- コメント --}}
            <div class="action">
                <span class="icon">💬</span>
                <span class="count">{{ $item->comments->count() }}</span>
            </div>
        </div>

        <!-- 下記は購入ページへのリンク -->
        @if ($item->purchase) {{-- purchase リレーションが存在するかどうか --}}
            <button class="btn-purchase sold-out" disabled>売り切れました</button>
        @else
            <a href="{{ route('purchase.index', $item->id) }}" class="btn-purchase">購入手続きへ</a>
        @endif

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
                    <div class="comment-header">
                        <div class="comment-user-image">
                            <img src="{{ $comment->user->profile->image_url ?? '' }}" alt="ユーザー画像">
                        </div>
                        <span class="comment-user-name">{{ $comment->user->profile->display_name ?? $comment->user->name }}</span>
                    </div>
                    <p class="comment-body">{{ $comment->body }}</p>
                </div>
            @endforeach

            <!-- コメント投稿フォーム -->
            <form action="{{ route('comment.store', $item->id) }}" method="post" class="comment-form">
                @csrf
                <textarea name="body" rows="3" placeholder="商品へのコメントを入力"></textarea>
                @auth
                    <button type="submit" class="btn-comment">コメントを送信する</button>
                @else
                    <button type="button" class="btn-comment disabled" disabled>コメントするにはログインが必要です</button>
                @endauth
            </form>
        </div>
    </div>
</div>
@endsection
