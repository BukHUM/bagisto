<?php

namespace Beyondary\Storefront\Theme;

use Illuminate\Support\Facades\Vite;
use Webkul\Theme\Theme;

class BeyondaryShopTheme extends Theme
{
    /**
     * Resolve theme assets with parent + default shop fallbacks so inherited
     * parent views do not abort(404) when the child Vite manifest omits images.
     */
    public function url(string $url): string
    {
        $resolved = $this->tryResolveFromVite($this->vite, $url);

        if ($resolved !== null) {
            return $resolved;
        }

        $parent = $this->getParent();

        if ($parent !== null) {
            $resolved = $this->tryResolveFromVite($parent->vite, $url);

            if ($resolved !== null) {
                return $resolved;
            }
        }

        $shopVite = config('bagisto-vite.viters.shop');

        if (is_array($shopVite)) {
            $resolved = $this->tryResolveFromVite($shopVite, $url);

            if ($resolved !== null) {
                return $resolved;
            }
        }

        report(new \RuntimeException(
            'Unable to locate storefront asset in any theme manifest: '.$url
        ));

        abort(404);
    }

    /**
     * @param  array<string, mixed>  $vite
     */
    protected function tryResolveFromVite(array $vite, string $url): ?string
    {
        if ($vite === []) {
            return null;
        }

        try {
            $viteUrl = trim($vite['package_assets_directory'], '/').'/'.trim($url, '/');

            return Vite::useHotFile($vite['hot_file'])
                ->useBuildDirectory($vite['build_directory'])
                ->asset($viteUrl);
        } catch (\Throwable) {
            return null;
        }
    }
}
