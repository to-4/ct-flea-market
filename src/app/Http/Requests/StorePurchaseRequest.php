<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // ゲストでも通す
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'address_id'        => ['required', 'exists:addresses,id'],
        ];
    }

    // カスタムメッセージ
    public function messages(): array
    {
        return [
            'payment_method_id.required' => '支払い方法が指定されていません',
            'payment_method_id.exists'   => '支払い方法が不正です',
            'address_id.required'        => '支払い先が指定されていません',
            'address_id.exists'          => '支払い先が存在しません',
        ];
    }
}
