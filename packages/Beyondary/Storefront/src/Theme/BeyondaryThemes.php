<?php

namespace Beyondary\Storefront\Theme;

use Illuminate\Support\Str;
use Webkul\Theme\Theme;
use Webkul\Theme\Themes;

class BeyondaryThemes extends Themes
{
    public function loadThemes(): void
    {
        $parentThemes = [];

        $isAdmin = false;

        try {
            $currentRequest = request();

            if ($currentRequest && $currentRequest->url()) {
                $isAdmin = Str::contains($currentRequest->url(), config('app.admin_url').'/');
            }
        } catch (\Exception) {
            $isAdmin = false;
        }

        $themes = $isAdmin
            ? config('themes.admin', [])
            : config('themes.shop', []);

        foreach ($themes as $code => $data) {
            $this->themes[] = $this->makeTheme(
                $code,
                $data['name'] ?? '',
                $data['assets_path'] ?? '',
                $data['views_path'] ?? '',
                $data['views_namespace'] ?? null,
                $data['vite'] ?? [],
            );

            if (! empty($data['parent'])) {
                $parentThemes[$code] = $data['parent'];
            }
        }

        foreach ($parentThemes as $childCode => $parentCode) {
            $child = $this->find($childCode);

            if ($this->exists($parentCode)) {
                $parent = $this->find($parentCode);
            } else {
                $parent = $this->makeTheme($parentCode);
            }

            $child->setParent($parent);
        }
    }

    /**
     * @param  array<string, mixed>  $vite
     */
    protected function makeTheme(
        string $code,
        ?string $name = null,
        ?string $assetsPath = null,
        ?string $viewsPath = null,
        ?string $viewsNamespace = null,
        array $vite = [],
    ): Theme {
        return new BeyondaryShopTheme(
            $code,
            $name,
            $assetsPath,
            $viewsPath,
            $viewsNamespace,
            $vite,
        );
    }
}
