<?php

namespace Beyondary\Storefront\Http\Middleware;

use Beyondary\Storefront\Services\AdminThemeService;
use Beyondary\Storefront\Theme\AdminThemeComponentRegistrar;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminThemeMiddleware
{
    public function __construct(
        protected AdminThemeService $adminThemeService,
        protected AdminThemeComponentRegistrar $componentRegistrar
    ) {}

    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->isAdminRequest($request)) {
            $themeCode = $this->adminThemeService->apply($request);

            $this->componentRegistrar->register($themeCode);
        }

        return $next($request);
    }

    protected function isAdminRequest(Request $request): bool
    {
        $adminUrl = trim((string) config('app.admin_url'), '/');

        if ($adminUrl === '') {
            return false;
        }

        return Str::contains($request->url(), '/'.$adminUrl.'/')
            || Str::endsWith($request->path(), $adminUrl);
    }
}
