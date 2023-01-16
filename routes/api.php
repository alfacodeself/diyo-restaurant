<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Controllers\API\{CartController, InventoryController, PaymentMethodController, UnitController, ProductController, ProductVariantController, SaleController};

Route::middleware(ApiKeyMiddleware::class)->group(function () {
    // =================> Route Master <================
    // Route Inventory
    Route::apiResource('units', UnitController::class)->except('show');
    // Route Inventory
    Route::apiResource('inventories', InventoryController::class);
    // Route Product
    Route::apiResource('products', ProductController::class);
    // Route Variant Product
    Route::apiResource('products/{product}/variants', ProductVariantController::class)->except('show');
    // Route Sale
    Route::apiResource('payments', PaymentMethodController::class)->except('show');
    
    // =================> Route Transacion <================
    // Route Cart
    Route::prefix('carts')->group(function () {
        // Route list all of cart
        Route::get('/', [CartController::class, 'getAllCarts']);
        // Route when customer choose his product {require: product_id}
        Route::get('{product}/add-cart', [CartController::class, 'addProductToCart']);
        // Route when after choose product, customer choose his variant {require: [ cart_id, product_variant_id ]}
        Route::get('{cart}/add-variants/{variant}', [CartController::class, 'addVariantsToProductCart']);
        // Route when after choose variant, customer want to delete or change his variant {require: [ cart_id, product_variant_id ]}
        Route::delete('{cart}/delete-variants/{variant}', [CartController::class, 'deleteVariantInProductCart']);
        
        Route::delete('{cart}/delete', [CartController::class, 'deleteCart']);
    });
    // Route Sale
    Route::apiResource('sales', SaleController::class)->only('show', 'index', 'store');

});
