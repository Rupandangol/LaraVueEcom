<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'total_price' => ['required'],
            // 'country'=>['required'],
            // 'zone'=>['required'],
            // 'district'=>['required'],
            // 'street'=>['required'],
            // 'zip_code'=>['required'],
            'product_id' => ['required'],
            'product_id.*' => ['required'],
            'quantity' => ['required'],
            'quantity.*' => ['required'],
            'price' => ['required'],
            'price.*' => ['required'],
        ];
    }
}
