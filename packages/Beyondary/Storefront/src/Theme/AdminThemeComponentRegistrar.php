<?php

namespace Beyondary\Storefront\Theme;

class AdminThemeComponentRegistrar
{
    protected const ADMIN_PACKAGE_VIEWS = 'packages/Webkul/Admin/src/Resources/views';

    protected const ADMIN_PACKAGE_COMPONENTS = 'packages/Webkul/Admin/src/Resources/views/components';

    protected const BEYONDARY_ADMIN_VIEWS = 'resources/admin-themes/beyondary-admin/views';

    protected const BEYONDARY_ADMIN_COMPONENTS = 'resources/admin-themes/beyondary-admin/views/components';

    protected ?string $registeredTheme = null;

    public function register(string $themeCode): void
    {
        if ($this->registeredTheme === $themeCode) {
            return;
        }

        $viewFinder = app('view.finder');

        if ($themeCode === 'beyondary-admin') {
            $viewFinder->replaceNamespace('admin', [
                base_path(self::BEYONDARY_ADMIN_VIEWS),
                base_path(self::ADMIN_PACKAGE_VIEWS),
            ]);

            $this->replaceAnonymousComponentPath(base_path(self::BEYONDARY_ADMIN_COMPONENTS));
        } else {
            $viewFinder->replaceNamespace('admin', [
                base_path(self::ADMIN_PACKAGE_VIEWS),
            ]);

            $this->replaceAnonymousComponentPath(base_path(self::ADMIN_PACKAGE_COMPONENTS));
        }

        $viewFinder->flush();

        $this->registeredTheme = $themeCode;
    }

    protected function replaceAnonymousComponentPath(string $componentRoot): void
    {
        $compiler = app('blade.compiler');

        $property = new \ReflectionProperty($compiler, 'anonymousComponentPaths');
        $property->setAccessible(true);

        $property->setValue($compiler, [[
            'path' => $componentRoot,
            'prefix' => 'admin',
            'prefixHash' => hash('xxh128', 'admin'),
        ]]);

        app('view.finder')->replaceNamespace(hash('xxh128', 'admin'), $componentRoot);
    }
}
