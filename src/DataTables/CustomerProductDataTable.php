<?php

declare(strict_types=1);

namespace Molitor\CustomerProduct\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Molitor\Admin\DataTables\DataTable;
use Molitor\CustomerProduct\Http\Resources\CustomerProductResource;
use Molitor\CustomerProduct\Models\CustomerProduct;

class CustomerProductDataTable extends DataTable
{
    protected function getModelClass(): string
    {
        return CustomerProduct::class;
    }

    protected function getResourceClass(): string
    {
        return CustomerProductResource::class;
    }

    protected function initColumns(): void
    {
        $this->addColumn('sku')->setSearchable()->setOrderable();
        $this->addColumn('name')->setSearchable();
        $this->addColumn('description')->setSearchable();
        $this->addColumn('keywords')->setSearchable()->setQueryName('customer_product_translations.keywords');
    }

    public function query(Builder $query): Builder
    {
        return $query
            ->joinTranslation()
            ->selectBase()
            ->with(['customer', 'currency', 'productUnit', 'customerProductCategories']);
    }
}
