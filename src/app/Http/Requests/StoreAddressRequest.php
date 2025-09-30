<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
            'postal_code' => [
                'required',
                'regex:/^\d{3}-\d{4}$/', // 999-9999 形式
            ],
            'address_line1' => [
                'required',
                'regex:/^[^\x01-\x7E]+$/u', // 全角文字のみ
                'max:120',
            ],
            'address_line2' => [
                'nullable',
                'regex:/^[^\x01-\x7E]+$/u', // 全角文字のみ
                'max:120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください',

            'address_line1.required' => '住所を入力してください',
            'address_line1.regex' => '住所は全角文字で入力してください',
            'address_line1.max' => '住所は 120 文字以内で入力してください',

            'address_line2.regex' => '建物は全角文字で入力してください',
            'address_line2.max' => '建物は 120 文字以内で入力してください',
        ];
    }

}
