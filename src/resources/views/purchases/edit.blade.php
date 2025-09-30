@extends('layouts.app')

@section('title', '住所の変更')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/purchases/edit.css') }}">
@endpush

@section('content')
<div class="address-edit-container">
    <h2 class="address-edit-title">住所の変更</h2>

    <form action="{{ route('purchase.store_address', ['item_id' => $item_id]) }}" method="POST" class="address-edit-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" 
                   value="{{ old('postal_code', $address->postal_code ?? '') }}">
            @error('postal_code')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address_line1">住所</label>
            <input type="text" id="address_line1" name="address_line1" 
                   value="{{ old('address_line1', $address->address_line1 ?? '') }}">
            @error('address_line1')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address_line2">建物名</label>
            <input type="text" id="address_line2" name="address_line2" 
                   value="{{ old('address_line2', $address->address_line2 ?? '') }}">
            @error('address_line2')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn-update">更新する</button>
        </div>
    </form>
</div>
@endsection
