<?php

namespace Beyondary\Storefront\Providers;

use Beyondary\Storefront\Console\Commands\ExportStorefrontCommand;
use Beyondary\Storefront\Console\Commands\ImportStorefrontCommand;
use Beyondary\Storefront\Console\Commands\InstallStorefrontPresetCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;

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
}
