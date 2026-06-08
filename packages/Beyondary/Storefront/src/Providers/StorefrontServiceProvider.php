<?php

namespace Beyondary\Storefront\Providers;

use Beyondary\Storefront\Console\Commands\ExportStorefrontCommand;
use Beyondary\Storefront\Console\Commands\ImportStorefrontCommand;
use Beyondary\Storefront\Console\Commands\InstallStorefrontPresetCommand;
use Beyondary\Storefront\Http\Controllers\Shop\ProductsCategoriesProxyController;
use Beyondary\Storefront\Http\Middleware\AdminThemeMiddleware;
use Beyondary\Storefront\Services\AdminThemeService;
use Beyondary\Storefront\Services\AdminThemeSettingsService;
use Beyondary\Storefront\Theme\AdminThemeComponentRegistrar;
use Beyondary\Storefront\Theme\BeyondaryThemes;
use Beyondary\Storefront\Theme\BeyondaryThemeViewFinder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController as BaseProductsCategoriesProxyController;
use Webkul\Theme\Themes;

class StorefrontServiceProvider extends ServiceProvider
{
    protected array $commands = [
        ExportStorefrontCommand::class,
        ImportStorefrontCommand::class,
        InstallStorefrontPresetCommand::class,
    ];

    /**
     * Register Beyondary storefront admin bindings (upgrade-safe Layer 4).
     */
    public function register(): void
    {
        $this->app->singleton(Themes::class, BeyondaryThemes::class);

        $this->app->bind(
            BaseProductsCategoriesProxyController::class,
            ProductsCategoriesProxyController::class
        );

        $this->app->singleton(AdminThemeService::class);

        $this->app->singleton(AdminThemeSettingsService::class);

        $this->app->singleton(AdminThemeComponentRegistrar::class);

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerBeyondaryViewFinder();

        Route::pushMiddlewareToGroup('web', AdminThemeMiddleware::class);

        $this->app->booted(function () {
            $this->syncViewFactoryFinder();

            app(AdminThemeComponentRegistrar::class)->register(
                (string) config('themes.admin-default', 'default')
            );
        });

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

        $this->loadTranslationsFrom(
            dirname(__DIR__, 2).'/resources/lang',
            'beyondary-storefront'
        );

        $this->loadViewsFrom(
            dirname(__DIR__, 2).'/resources/views',
            'beyondary-storefront'
        );

        Route::middleware(['web', 'admin', NoCacheMiddleware::class])
            ->prefix(config('app.admin_url'))
            ->group(dirname(__DIR__).'/Routes/admin-routes.php');
    }

    protected function registerBeyondaryViewFinder(): void
    {
        $this->app->singleton('view.finder', function ($app) {
            return new BeyondaryThemeViewFinder(
                $app['files'],
                $app['config']['view.paths'],
                null
            );
        });

        $this->app->resolving('view', function ($viewFactory, $app) {
            $viewFactory->setFinder($app->make('view.finder'));
        });
    }

    protected function syncViewFactoryFinder(): void
    {
        if (! $this->app->resolved('view')) {
            return;
        }

        $this->app->make('view')->setFinder($this->app->make('view.finder'));
    }
}
