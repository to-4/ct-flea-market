<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 商品画像
            'image_url' => ['required', 'mimes:png,jpeg'],

            // カテゴリ（配列で1つ以上必須）
            'categories' => ['required', 'array', 'min:1'],

            // 商品の状態
            'item_condition_id' => ['required'],

            // 商品名
            'name' => ['required', 'max:120'],

            // ブランド名
            'bland_name' => ['required', 'max:120'],

            // 商品の説明
            'description' => ['required'],

            // 値段
            'price' => ['required', 'numeric', 'between:1,1000000'],
        ];
    }

    public function messages(): array
    {
        return [
            'image_url.required' => '商品画像を登録してください',
            'image_url.mimes'    => '.png または .jpeg 形式でアップロードしてください',

            'categories.required' => 'カテゴリは一つ以上選択してください',
            'categories.min'      => 'カテゴリは一つ以上選択してください',

            'item_condition_id.required' => '商品の状態を選択してください',

            'name.required' => '商品名を入力してください',
            'name.max'      => '商品名は 120 文字以内で入力してください',

            'bland_name.required' => 'ブランド名を入力してください',
            'bland_name.max'      => 'ブランド名は 120 文字以内で入力してください',

            'description.required' => '商品の説明を入力してください',

            'price.required' => '値段を入力してください',
            'price.numeric'  => '数値で入力してください',
            'price.between'  => '1 ～ 1,000,000円以内で入力してください',
        ];
    }
}
