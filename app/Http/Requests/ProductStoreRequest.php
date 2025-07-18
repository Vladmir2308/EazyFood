<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'name' => 'required',
            'category_id' => '',
            'category_name' => '',
            'default_unit' => '',
            'user_id' => '',
            'type' => '',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Продукт то напиши',
        ];
    }
}
