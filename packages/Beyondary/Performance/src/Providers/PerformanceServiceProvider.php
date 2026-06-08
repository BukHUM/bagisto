<?php

namespace Beyondary\Performance\Providers;

use Beyondary\Performance\DataGrids\Admin\Sales\OrderDataGrid as BeyondaryOrderDataGrid;
use Beyondary\Performance\Repositories\ProductRepository as BeyondaryProductRepository;
use Illuminate\Support\ServiceProvider;
use Webkul\Admin\DataGrids\Sales\OrderDataGrid;
use Webkul\Product\Repositories\ProductRepository;

class PerformanceServiceProvider extends ServiceProvider
{
    /**
     * Register Beyondary performance bindings (upgrade-safe Layer 4).
     */
    public function register(): void
    {
        $this->app->bind(ProductRepository::class, BeyondaryProductRepository::class);

        $this->app->bind(OrderDataGrid::class, BeyondaryOrderDataGrid::class);
    }
}
