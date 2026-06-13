<?php

declare(strict_types=1);

namespace Molitor\CustomerProduct\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerProductCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'parent_id' => $this->parent_id,
            'left_value' => $this->left_value,
            'right_value' => $this->right_value,
            'name' => $this->name,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'url' => $this->url,
            'image_url' => $this->image_url,
            'translations' => $this->translationsToArray(),
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer?->id,
                'name' => $this->customer?->name,
            ]),
            'parent' => $this->whenLoaded('parent', fn () => [
                'id' => $this->parent?->id,
                'name' => $this->parent?->getAttributeTranslation('name'),
            ]),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}