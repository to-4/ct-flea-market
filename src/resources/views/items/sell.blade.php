@extends('layouts.app')

@section('title', '商品の出品')

@push('page-css')
<link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endpush

@section('content')
<div class="sell-container">
    <h2 class="sell-title">商品の出品</h2>

    <form action="{{ route('sell.post') }}" method="post" enctype="multipart/form-data" class="sell-form">
        @csrf

        {{-- 商品画像 --}}
        <div class="form-section">
            <label class="section-label">商品画像</label>

            <div class="image-upload" id="image_upload_container">
                <input type="file" name="image_url" id="image_url" hidden>
                <label for="image_url" class="btn-image-upload" id="image_select_btn">画像を選択する</label>

                <div class="image-preview" id="image_preview_container" style="display: none;">
                    <button type="button" id="image_cancel_btn" class="btn-cancel">✕</button>
                    <img id="image_preview" alt="選択した商品のプレビュー">
                </div>
            </div>


            @error('image_url')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品の詳細 --}}
        <div class="form-section">
            <label class="section-label">商品詳細</label>
            <div class="form-group">
                <label>カテゴリー</label>
                <div class="category-tags">
                    @php
                        $selectedCategories = array_map('intval', old('categories', []));
                    @endphp
                    @foreach($categories as $category)
                        <label class="category-tag">
                            <input class="category-tag-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                            <span class="category-tag-label">{{ $category->name }}</span>
                        </label>
                    @endforeach
                    @error('categories')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="item_condition_id">商品の状態</label>
                <select name="item_condition_id" id="item_condition_id">
                    <option value="">選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}"
                            {{ old('item_condition_id') == $condition->id ? 'selected' : '' }}>
                            {{ $condition->name }}
                        </option>
                    @endforeach
                </select>
                @error('item_condition_id')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 商品名と説明 --}}
        <div class="form-section">
            <label class="section-label">商品名と説明</label>

            <div class="form-group">
                <label for="name">商品名</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}">
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="brand_name">ブランド名</label>
                <input type="text" name="brand_name" id="brand_name" value="{{ old('brand_name') }}">
                @error('brand_name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">商品の説明</label>
                <textarea name="description" id="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 販売価格 --}}
        <div class="form-section">
            <label class="section-label">販売価格</label>
            <div class="form-group">
                <input type="number" name="price" id="price" value="{{ old('price') }}" >
                @error('price')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 出品ボタン --}}
        <div class="form-group">
            <button type="submit" class="btn-sell">出品する</button>
        </div>
    </form>
</div>
@endsection

@push('page-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('image_url');
    const preview = document.getElementById('image_preview');
    const previewContainer = document.getElementById('image_preview_container');
    const selectBtn = document.getElementById('image_select_btn');
    const cancelBtn = document.getElementById('image_cancel_btn');

    if (!input || !preview || !previewContainer || !selectBtn || !cancelBtn) {
        return;
    }

    const showPreview = function (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            preview.src = event.target.result;
            selectBtn.style.display = 'none';
            previewContainer.style.display = 'block';
            document.getElementById('image_upload_container').classList.add('has-preview');
        };
        reader.readAsDataURL(file);
    };

    input.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (file) {
            showPreview(file);
        }
    });

    cancelBtn.addEventListener('click', function () {
        input.value = '';
        preview.removeAttribute('src');
        previewContainer.style.display = 'none';
        selectBtn.style.display = 'inline-block';
        document.getElementById('image_upload_container').classList.remove('has-preview');
    });
});
</script>
@endpush
