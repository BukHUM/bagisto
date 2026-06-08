<?php

namespace Beyondary\Storefront\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController as BaseProductsCategoriesProxyController;

class ProductsCategoriesProxyController extends BaseProductsCategoriesProxyController
{
    /**
     * Bagisto aborts when the active locale has no url_key even though findBySlug()
     * matched another locale. Skip that check — the slug match is sufficient.
     */
    public function index(Request $request): View|\Exception
    {
        $slugOrURLKey = urldecode(trim($request->getPathInfo(), '/'));

        if (! preg_match('/^([\p{L}\p{N}\p{M}\x{0900}-\x{097F}\x{0590}-\x{05FF}\x{0600}-\x{06FF}\x{0400}-\x{04FF}_-]+\/?)+$/u', $slugOrURLKey)) {
            $customizations = $this->themeCustomizationRepository->orderBy('sort_order')->findWhere([
                'status' => self::STATUS,
                'channel_id' => core()->getCurrentChannel()->id,
            ]);

            return view('shop::home.index', compact('customizations'));
        }

        $category = $this->categoryRepository->findBySlug($slugOrURLKey);

        if ($category) {
            return view('shop::categories.view', [
                'category' => $category,
                'params' => [
                    'sort' => request()->query('sort'),
                    'limit' => request()->query('limit'),
                    'mode' => request()->query('mode'),
                ],
            ]);
        }

        $searchEngine = null;

        if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
            $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
        }

        $product = $this->productRepository
            ->setSearchEngine($searchEngine ?? 'database')
            ->findBySlug($slugOrURLKey);

        if ($product) {
            if (! $product->visible_individually || ! $product->status) {
                abort(404);
            }

            $productURLRewrite = $this->urlRewriteRepository->findOneWhere([
                'entity_type' => 'product',
                'request_path' => $slugOrURLKey,
                'locale' => app()->getLocale(),
            ]);

            if ($productURLRewrite) {
                return redirect()->to($productURLRewrite->target_path, $productURLRewrite->redirect_type);
            }

            return view('shop::products.view', compact('product'));
        }

        if (str_contains($slugOrURLKey, '/')) {
            $trimmedSlug = last(explode('/', $slugOrURLKey));

            $category = $this->categoryRepository->findBySlug($trimmedSlug);

            if ($category) {
                return redirect()->to($trimmedSlug, 301);
            }
        }

        $urlRewrite = $this->urlRewriteRepository->findOneWhere([
            'request_path' => $slugOrURLKey,
            'locale' => app()->getLocale(),
        ]);

        if ($urlRewrite) {
            return redirect()->to($urlRewrite->target_path, $urlRewrite->redirect_type);
        }

        abort(404);
    }
}
