<?php

declare(strict_types=1);

namespace Molitor\CustomerProduct\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'same_customer_product' => $this->same_customer_product,
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'url' => $this->url,
            'price' => $this->price,
            'currency_id' => $this->currency_id,
            'stock' => $this->stock,
            'product_unit_id' => $this->product_unit_id,
            'translations' => $this->translationsToArray(),
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer?->id,
                'name' => $this->customer?->name,
            ]),
            'currency' => $this->whenLoaded('currency', fn () => [
                'id' => $this->currency?->id,
                'name' => $this->currency?->name,
            ]),
            'product_unit' => $this->whenLoaded('productUnit', fn () => [
                'id' => $this->productUnit?->id,
                'name' => $this->productUnit?->name,
            ]),
            'customer_product_category_ids' => $this->whenLoaded('customerProductCategories', fn () => $this->customerProductCategories->pluck('id')->values()),
            'customer_product_categories' => $this->whenLoaded('customerProductCategories', fn () => $this->customerProductCategories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->getAttributeTranslation('name'),
            ])->values()),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}