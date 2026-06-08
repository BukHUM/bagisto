<?php

namespace Beyondary\Performance\DataGrids\Admin\Sales;

use Illuminate\Support\Collection;
use Webkul\Admin\DataGrids\Sales\OrderDataGrid as BaseOrderDataGrid;
use Webkul\Sales\Repositories\OrderRepository;

class OrderDataGrid extends BaseOrderDataGrid
{
    /**
     * Orders with items preloaded for the current page.
     */
    protected Collection $ordersWithItems;

    /**
     * Use preloaded orders in the items column instead of per-row queries.
     */
    public function prepareColumns(): void
    {
        parent::prepareColumns();

        foreach ($this->getColumns() as $column) {
            if ($column->getIndex() !== 'items') {
                continue;
            }

            $column->setClosure(function ($value) {
                $order = $this->ordersWithItems->get($value->id);

                if (! $order) {
                    return '';
                }

                return view('admin::sales.orders.items', compact('order'))->render();
            });

            break;
        }
    }

    /**
     * Preload order items for the current page to avoid N+1 queries.
     */
    protected function formatRecords($records): mixed
    {
        $orderIds = collect($records)->pluck('id')->filter()->unique()->values()->all();

        $this->ordersWithItems = empty($orderIds)
            ? collect()
            : app(OrderRepository::class)
                ->with(['items.product.images'])
                ->findWhereIn('id', $orderIds)
                ->keyBy('id');

        return parent::formatRecords($records);
    }
}
