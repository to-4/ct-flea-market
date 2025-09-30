@extends('layouts.app')

@section('title', 'プロフィール設定')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/mypages/edit.css') }}">
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
                <img id="profile_preview"
                    src="{{ optional($profile)->image_url ? $profile->image_url : '' }}"
                    alt="プロフィール画像"
                    style="{{ optional($profile)->image_url ? '' : 'display:none;' }}">
            </div>
            <label class="btn-image-upload">
                画像を選択する
                <input type="file" name="image_url" id="image_url" hidden>
            </label>
            @error('image_url')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="displayName">ユーザー名</label>
            <input type="text" id="displayName" name="displayName" value="{{ old('displayName', $profile->display_name) }}" >
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

@push('page-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('image_url');
    const preview = document.getElementById('profile_preview');

    if (input && preview) {
        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
