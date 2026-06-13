<?php

declare(strict_types=1);

namespace Molitor\CustomerProduct\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'parent_id' => ['required', 'integer', 'min:0'],
            'url' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'translations.*.keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
}