<?php

namespace Beyondary\Storefront\Services;

use Illuminate\Http\Request;
use Webkul\Theme\Themes;

class AdminThemeService
{
    protected bool $adminThemesBooted = false;

    public const COOKIE_NAME = 'admin_theme';

    /**
     * @return array<string, array{code: string, name: string}>
     */
    public function availableThemes(): array
    {
        $themes = [];

        foreach (config('themes.admin', []) as $code => $data) {
            $themes[$code] = [
                'code' => $code,
                'name' => (string) ($data['name'] ?? $code),
            ];
        }

        return $themes;
    }

    public function isValid(string $code): bool
    {
        return array_key_exists($code, config('themes.admin', []));
    }

    public function defaultThemeCode(): string
    {
        return (string) config('themes.admin-default', 'default');
    }

    public function resolve(?Request $request = null): string
    {
        $request ??= request();

        if ($request) {
            $cookieTheme = $request->cookie(self::COOKIE_NAME);

            if (is_string($cookieTheme) && $this->isValid($cookieTheme)) {
                return $cookieTheme;
            }
        }

        return $this->defaultThemeCode();
    }

    public function apply(Request $request): string
    {
        $this->bootAdminThemes();

        $themeCode = $this->resolve($request);

        themes()->set($themeCode);

        return $themeCode;
    }

    protected function bootAdminThemes(): void
    {
        if ($this->adminThemesBooted) {
            return;
        }

        /** @var Themes $manager */
        $manager = themes();

        $property = new \ReflectionProperty($manager, 'themes');
        $property->setAccessible(true);
        $property->setValue($manager, []);

        $manager->loadThemes();

        $this->adminThemesBooted = true;
    }
}
