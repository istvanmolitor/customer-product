<?php

declare(strict_types=1);

namespace Molitor\CustomerProduct\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Molitor\CustomerProduct\DataTables\CustomerProductCategoryDataTable;
use Molitor\Customer\Models\Customer;
use Molitor\CustomerProduct\Http\Requests\StoreCustomerProductCategoryRequest;
use Molitor\CustomerProduct\Http\Resources\CustomerProductCategoryResource;
use Molitor\CustomerProduct\Models\CustomerProductCategory;

class CustomerProductCategoryApiController extends Controller
{
    public function index(CustomerProductCategoryDataTable $dataTable): AnonymousResourceCollection
    {
        return $dataTable->getResponse();
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'customers' => Customer::query()->orderBy('name')->pluck('name', 'id'),
            'customer_product_categories' => CustomerProductCategory::query()->joinTranslation()->selectBase()->orderByTranslation('name')->get(),
        ]);
    }

    public function store(StoreCustomerProductCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $customerProductCategory = CustomerProductCategory::create([
            'customer_id' => $validated['customer_id'],
            'parent_id' => $validated['parent_id'],
            'url' => $validated['url'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
        ]);

        $customerProductCategory->setRequestTranslations($validated);
        $customerProductCategory->save();

        $customerProductCategory->load(['customer', 'parent', 'translations']);

        return response()->json([
            'data' => new CustomerProductCategoryResource($customerProductCategory),
            'message' => __('customer_product::common.created'),
        ], 201);
    }

    public function show(CustomerProductCategory $customerProductCategory): JsonResponse
    {
        $customerProductCategory->load(['customer', 'parent', 'translations']);

        return response()->json([
            'data' => new CustomerProductCategoryResource($customerProductCategory),
        ]);
    }

    public function edit(CustomerProductCategory $customerProductCategory): JsonResponse
    {
        $customerProductCategory->load(['customer', 'parent', 'translations']);

        return response()->json([
            'data' => new CustomerProductCategoryResource($customerProductCategory),
            'customers' => Customer::query()->orderBy('name')->pluck('name', 'id'),
            'customer_product_categories' => CustomerProductCategory::query()->joinTranslation()->selectBase()->orderByTranslation('name')->get(),
        ]);
    }

    public function update(StoreCustomerProductCategoryRequest $request, CustomerProductCategory $customerProductCategory): JsonResponse
    {
        $validated = $request->validated();

        $customerProductCategory->update([
            'customer_id' => $validated['customer_id'],
            'parent_id' => $validated['parent_id'],
            'url' => $validated['url'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
        ]);

        $customerProductCategory->setRequestTranslations($validated);
        $customerProductCategory->save();

        $customerProductCategory->load(['customer', 'parent', 'translations']);

        return response()->json([
            'data' => new CustomerProductCategoryResource($customerProductCategory),
            'message' => __('customer_product::common.updated'),
        ]);
    }

    public function destroy(CustomerProductCategory $customerProductCategory): JsonResponse
    {
        $customerProductCategory->delete();

        return response()->json([
            'message' => __('customer_product::common.deleted'),
        ]);
    }
}