<?php

declare(strict_types=1);

namespace Molitor\CustomerProduct\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Molitor\CustomerProduct\Models\CustomerProduct;

class StoreCustomerProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerProduct = $this->route('customerProduct');
        $ignoreId = $customerProduct instanceof CustomerProduct ? $customerProduct->getKey() : null;
        $customerId = $this->input('customer_id');

        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'same_customer_product' => ['nullable', 'integer', 'exists:customer_products,id'],
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customer_products', 'sku')
                    ->where(fn ($query) => $query->where('customer_id', $customerId))
                    ->ignore($ignoreId),
            ],
            'url' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'product_unit_id' => ['required', 'integer', 'exists:product_units,id'],
            'customer_product_category_ids' => ['sometimes', 'array'],
            'customer_product_category_ids.*' => ['integer', 'exists:customer_product_categories,id'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'translations.*.keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
}