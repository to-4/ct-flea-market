@extends('layouts.app')

@section('title', '商品購入')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/purchases/index.css') }}">
@endpush

@section('content')
<div class="purchase-container">
    <div class="purchase-left">
        <div class="purchase-item">
            <div class="purchase-item-image">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
            </div>
            <div class="purchase-item-info">
                <p class="purchase-item-name">{{ $item->name }}</p>
                <p class="purchase-item-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <div class="purchase-section">
            <h3>支払い方法</h3>
            <form action="{{ route('purchase.store', $item->id) }}" method="post">
                @csrf
                <select name="payment_method_id" class="payment-select">
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="purchase-section">
            <h3>配送先 <a href="{{ route('purchase.edit', ['item_id' => $item->id, 'address_id' => $userAddress->id]) }}" class="address-edit">変更する</a></h3>
            <p>〒{{ $userAddress->postal_code }}</p>
            <p>{{ $userAddress->address_line1 }} {{ $userAddress->address_line2 }}</p>
        </div>
    </div>

    <div class="purchase-right">
        <div class="summary-box">
            <p>商品代金 <span>¥{{ number_format($item->price) }}</span></p>
            <p>支払い方法 <span>{{ $paymentMethods->first()->name ?? '' }}</span></p>
        </div>
        <form action="{{ route('purchase.store', $item->id) }}" method="post">
            @csrf
            <button type="submit" class="btn-purchase">購入する</button>
        </form>
    </div>
</div>
@endsection
