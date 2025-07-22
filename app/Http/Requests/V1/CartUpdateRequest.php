<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateRequest extends FormRequest
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
        if ($this->method == 'PUT') {
            return [
                'product_id' => ['required'],
                'quantity' => ['required'],
            ];
        } else {
            return [
                'product_id' => ['sometimes', 'required'],
                'quantity' => ['sometimes', 'required'],
            ];
        }

    }
}
