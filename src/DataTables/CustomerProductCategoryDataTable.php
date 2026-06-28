<?php

declare(strict_types=1);

namespace Molitor\CustomerProduct\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Molitor\Admin\DataTables\DataTable;
use Molitor\CustomerProduct\Http\Resources\CustomerProductCategoryResource;
use Molitor\CustomerProduct\Models\CustomerProductCategory;

class CustomerProductCategoryDataTable extends DataTable
{
    protected function getModelClass(): string
    {
        return CustomerProductCategory::class;
    }

    protected function getResourceClass(): string
    {
        return CustomerProductCategoryResource::class;
    }

    protected function initColumns(): void
    {
        $this->addColumn('name')->setSearchable()->setOrderable();
        $this->addColumn('description')->setSearchable();
        $this->addColumn('keywords')->setSearchable();
        $this->addColumn('url')->setSearchable();
    }

    public function query(Builder $query): Builder
    {
        return $query
            ->joinTranslation()
            ->selectBase()
            ->with(['customer', 'parent']);
    }
}
