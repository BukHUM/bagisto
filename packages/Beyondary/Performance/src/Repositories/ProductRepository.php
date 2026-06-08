<?php

namespace Beyondary\Performance\Repositories;

use Beyondary\Performance\Concerns\ImprovesProductDatabaseSearch;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class ProductRepository extends BaseProductRepository
{
    use ImprovesProductDatabaseSearch;
}
