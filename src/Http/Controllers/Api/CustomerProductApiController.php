<?php

declare(strict_types=1);

namespace Molitor\CustomerProduct\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\Currency\Models\Currency;
use Molitor\Customer\Models\Customer;
use Molitor\CustomerProduct\Http\Requests\StoreCustomerProductRequest;
use Molitor\CustomerProduct\Http\Resources\CustomerProductResource;
use Molitor\CustomerProduct\Models\CustomerProduct;
use Molitor\CustomerProduct\Models\CustomerProductCategory;
use Molitor\CustomerProduct\Repositories\CustomerProductCategoryProductRepositoryInterface;
use Molitor\Language\Models\Language;
use Molitor\Product\Models\ProductUnit;

class CustomerProductApiController extends Controller
{
    use HasAdminFilters;

    public function __construct(
        private CustomerProductCategoryProductRepositoryInterface $customerProductCategoryProductRepository,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $query = CustomerProduct::query()->joinTranslation()->selectBase()->with([
            'customer',
            'currency',
            'productUnit',
            'customerProductCategories',
        ]);

        $customerProducts = $this->applyAdminFilters($query, $request, ['sku', 'name', 'description', 'keywords'])
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => CustomerProductResource::collection($customerProducts->items()),
            'meta' => [
                'current_page' => $customerProducts->currentPage(),
                'last_page' => $customerProducts->lastPage(),
                'per_page' => $customerProducts->perPage(),
                'total' => $customerProducts->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'customers' => Customer::query()->orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::query()->orderBy('name')->pluck('name', 'id'),
            'languages' => Language::query()->orderBy('name')->pluck('name', 'id'),
            'product_units' => ProductUnit::query()->joinTranslation()->selectBase()->orderByTranslation('name')->get(),
            'customer_product_categories' => CustomerProductCategory::query()->joinTranslation()->selectBase()->orderByTranslation('name')->get(),
        ]);
    }

    public function store(StoreCustomerProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $customerProduct = CustomerProduct::create([
            'customer_id' => $validated['customer_id'],
            'product_id' => $validated['product_id'] ?? null,
            'same_customer_product' => $validated['same_customer_product'] ?? null,
            'sku' => $validated['sku'],
            'url' => $validated['url'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency_id' => $validated['currency_id'] ?? null,
            'stock' => $validated['stock'] ?? null,
            'product_unit_id' => $validated['product_unit_id'] ?? null,
        ]);

        $customerProduct->setRequestTranslations($validated);
        $customerProduct->save();

        if (array_key_exists('customer_product_category_ids', $validated)) {
            $this->customerProductCategoryProductRepository->setProductValues(
                $customerProduct,
                $validated['customer_product_category_ids'],
                true,
            );
        }

        $customerProduct->load(['customer', 'currency', 'productUnit', 'customerProductCategories', 'translations']);

        return response()->json([
            'data' => new CustomerProductResource($customerProduct),
            'message' => __('customer_product::common.created'),
        ], 201);
    }

    public function show(CustomerProduct $customerProduct): JsonResponse
    {
        $customerProduct->load(['customer', 'currency', 'productUnit', 'customerProductCategories', 'translations']);

        return response()->json([
            'data' => new CustomerProductResource($customerProduct),
        ]);
    }

    public function edit(CustomerProduct $customerProduct): JsonResponse
    {
        $customerProduct->load(['customer', 'currency', 'productUnit', 'customerProductCategories', 'translations']);

        return response()->json([
            'data' => new CustomerProductResource($customerProduct),
            'customers' => Customer::query()->orderBy('name')->pluck('name', 'id'),
            'currencies' => Currency::query()->orderBy('name')->pluck('name', 'id'),
            'languages' => Language::query()->orderBy('name')->pluck('name', 'id'),
            'product_units' => ProductUnit::query()->joinTranslation()->selectBase()->orderByTranslation('name')->get(),
            'customer_product_categories' => CustomerProductCategory::query()->joinTranslation()->selectBase()->orderByTranslation('name')->get(),
        ]);
    }

    public function update(StoreCustomerProductRequest $request, CustomerProduct $customerProduct): JsonResponse
    {
        $validated = $request->validated();

        $customerProduct->update([
            'customer_id' => $validated['customer_id'],
            'product_id' => $validated['product_id'] ?? null,
            'same_customer_product' => $validated['same_customer_product'] ?? null,
            'sku' => $validated['sku'],
            'url' => $validated['url'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency_id' => $validated['currency_id'] ?? null,
            'stock' => $validated['stock'] ?? null,
            'product_unit_id' => $validated['product_unit_id'] ?? null,
        ]);

        $customerProduct->setRequestTranslations($validated);
        $customerProduct->save();

        if (array_key_exists('customer_product_category_ids', $validated)) {
            $this->customerProductCategoryProductRepository->setProductValues(
                $customerProduct,
                $validated['customer_product_category_ids'],
                true,
            );
        }

        $customerProduct->load(['customer', 'currency', 'productUnit', 'customerProductCategories', 'translations']);

        return response()->json([
            'data' => new CustomerProductResource($customerProduct),
            'message' => __('customer_product::common.updated'),
        ]);
    }

    public function destroy(CustomerProduct $customerProduct): JsonResponse
    {
        $customerProduct->delete();

        return response()->json([
            'message' => __('customer_product::common.deleted'),
        ]);
    }
}