<?php

use Illuminate\Support\Facades\Route;
use Molitor\CustomerProduct\Http\Controllers\Api\CustomerProductApiController;
use Molitor\CustomerProduct\Http\Controllers\Api\CustomerProductCategoryApiController;

Route::prefix('admin/customer-product')
    ->middleware(['api', 'auth:sanctum', 'permission:customer_product'])
    ->name('customer-product.')
    ->group(function () {
        Route::resource('customer-products', CustomerProductApiController::class);
        Route::resource('customer-product-categories', CustomerProductCategoryApiController::class);
    });