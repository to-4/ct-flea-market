@extends('layouts.app')

@section('title', '商品購入')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/purchases/index.css') }}">
@endpush

@section('content')
<div class="purchase-container">
    <form action="{{ route('purchase.store') }}" method="post" class="purchase-form">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="hidden" name="address_id" value="{{ $userAddress->id }}">
        {{-- 左カラム --}}
        <div class="purchase-left">
            {{-- 商品情報 --}}
            <div class="purchase-item purchase-section">
                <div class="purchase-item-image">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                </div>
                <div class="purchase-item-info">
                    <p class="purchase-item-name">{{ $item->name }}</p>
                    <p class="purchase-item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            {{-- 支払い方法 --}}
            <div class="purchase-section supplement">
                <h3 class="section-title">支払い方法</h3>
                <select name="payment_method_id" id="payment_method" class="payment-select">
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 配送先 --}}
            <div class="purchase-section no-border supplement">
                <div class="purchase-section-header">
                    <h3 class="section-title">配送先</h3>
                    <a href="{{ route('purchase.edit', ['item_id' => $item->id, 'address_id' => $userAddress->id]) }}" class="address-edit">変更する</a>
                </div>
                <div class="address-info">
                    <p>〒{{ $userAddress->postal_code }}</p>
                    <p>{{ $userAddress->address_line1 }} {{ $userAddress->address_line2 }}</p>
                </div>
            </div>
        </div>

        {{-- 右カラム --}}
        <div class="purchase-right">
            <div class="summary-box">
                <p class="summary-row">商品代金 <span>¥{{ number_format($item->price) }}</span></p>
                <p class="summary-row">支払い方法 <span id="summary_payment">{{ $paymentMethods->first()->name ?? '' }}</span></p>
            </div>
            <button type="submit" class="btn-purchase">購入する</button>
        </div>
    </form>
</div>
@endsection

@push('page-js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('payment_method');
        const summaryPayment = document.getElementById('summary_payment');

        if (select && summaryPayment) {
            select.addEventListener('change', function () {
                summaryPayment.textContent = this.options[this.selectedIndex].text;
            });
        }
    });
</script>
@endpush
