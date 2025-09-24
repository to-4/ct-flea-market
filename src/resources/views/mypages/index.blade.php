@extends('layouts.app')

@section('title', 'プロフィール設定')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/mypages/index.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <h2 class="profile-title">プロフィール設定</h2>

    <form action="{{ ($profile->exists ?? false) ? route('mypage.update', $profile->id) : route('mypage.store') }}" method="post" enctype="multipart/form-data" class="profile-form">
        @csrf
        @if($profile->exists ?? false)
            @method('PUT')
        @endif

        <div class="profile-image-section">
            <div class="profile-image">
                <img src="{{ $profile->image_url ?? asset('images/default-user.png') }}" alt="プロフィール画像">
            </div>
            <label class="btn-image-upload">
                画像を選択する
                <input type="file" name="image_url" hidden>
                @error('image_url')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </label>
        </div>

        <div class="form-group">
            <label for="displayName">ユーザー名</label>
            <input type="text" id="displayName" name="displayName" value="{{ old('displayName', $profile->displayName) }}" >
            @error('displayName')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->address->postal_code ?? '') }}">
            @error('postal_code')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address_line1">住所</label>
            <input type="text" id="address_line1" name="address_line1" value="{{ old('address_line1', $profile->address->address_line1 ?? '') }}">
            @error('address_line1')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address_line2">建物名</label>
            <input type="text" id="address_line2" name="address_line2" value="{{ old('address_line2', $profile->address->address_line2 ?? '') }}">
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
