<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'body' => ['required', 'max:254']
        ];
    }

    // カスタムメッセージ
    public function messages(): array
    {
        return [
            'body.required' => 'コメントを入力してください',
            'body.max'      => 'コメントは254文字以内で入力してください',
        ];
    }
}
