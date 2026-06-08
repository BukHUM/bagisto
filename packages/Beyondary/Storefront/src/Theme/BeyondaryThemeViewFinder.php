<?php

namespace Beyondary\Storefront\Theme;

use Beyondary\Storefront\Services\AdminThemeService;
use Illuminate\Support\Arr;
use Webkul\Theme\ThemeViewFinder;

class BeyondaryThemeViewFinder extends ThemeViewFinder
{
    protected function setActiveTheme(bool $isAdmin): void
    {
        if ($isAdmin) {
            app(AdminThemeService::class)->apply(request());
        }
    }

    /**
     * Prefer active admin theme views over package defaults.
     */
    public function find($view)
    {
        if (isset($this->views[$view])) {
            return $this->views[$view];
        }

        if ($this->hasHintInformation($view)) {
            [$namespace, $viewName] = $this->parseNamespaceSegments($view);

            $themePath = $this->resolveActiveAdminThemeView($namespace, $viewName);

            if ($themePath !== null) {
                return $this->views[$view] = $themePath;
            }
        }

        return parent::find($view);
    }

    public function addThemeNamespacePaths($namespace)
    {
        $paths = parent::addThemeNamespacePaths($namespace);

        if ($namespace !== self::ADMIN_PACKAGE_VIEWS_NAMESPACE) {
            return $paths;
        }

        foreach ($this->activeAdminThemeViewRoots() as $root) {
            if (! in_array($root, $paths, true)) {
                $paths = Arr::prepend($paths, $root);
            }
        }

        return array_values(array_unique($paths));
    }

    protected function resolveActiveAdminThemeView(string $namespace, string $viewName): ?string
    {
        if ($namespace !== self::ADMIN_PACKAGE_VIEWS_NAMESPACE) {
            return null;
        }

        foreach ($this->activeAdminThemeViewRoots() as $root) {
            try {
                return $this->findInPaths($viewName, [$root]);
            } catch (\Exception) {
                continue;
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    protected function activeAdminThemeViewRoots(): array
    {
        $theme = themes()->current();

        if (! $theme || $theme->code === 'default') {
            return [];
        }

        $roots = [];

        foreach ($theme->getViewPaths() as $viewPath) {
            $absolute = $this->absoluteViewRoot($viewPath);

            if ($absolute !== null) {
                $roots[] = $absolute;
            }
        }

        return array_values(array_unique($roots));
    }

    protected function absoluteViewRoot(string $viewPath): ?string
    {
        if ($viewPath === '') {
            return null;
        }

        $absolute = str_starts_with($viewPath, DIRECTORY_SEPARATOR)
            || preg_match('/^[A-Za-z]:[\\\\\\/]/', $viewPath) === 1
            ? $viewPath
            : base_path($viewPath);

        return is_dir($absolute) ? $absolute : null;
    }
}
