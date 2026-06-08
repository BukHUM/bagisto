<?php

use Beyondary\Storefront\Http\Controllers\Admin\AdminThemeController;
use Beyondary\Storefront\Http\Controllers\Admin\HomeCustomizationController;
use Beyondary\Storefront\Http\Controllers\Admin\SectionController;
use Beyondary\Storefront\Http\Controllers\Admin\TransferController;
use Illuminate\Support\Facades\Route;

Route::prefix('beyondary')->group(function () {
    Route::controller(AdminThemeController::class)->prefix('admin-theme')->group(function () {
        Route::get('', 'index')->name('admin.beyondary.admin-theme.index');
        Route::post('', 'update')->name('admin.beyondary.admin-theme.update');
        Route::post('settings', 'updateSettings')->name('admin.beyondary.admin-theme.settings.update');
    });

    Route::controller(HomeCustomizationController::class)->group(function () {
        Route::get('storefront', 'index')->name('admin.beyondary.storefront.index');
    });

    Route::controller(SectionController::class)->prefix('storefront/sections')->group(function () {
        Route::get('{section}/edit', 'edit')->name('admin.beyondary.storefront.sections.edit');
        Route::post('{section}', 'update')->name('admin.beyondary.storefront.sections.update');
    });

    Route::controller(TransferController::class)->prefix('storefront')->group(function () {
        Route::get('export', 'export')->name('admin.beyondary.storefront.export');
        Route::post('import', 'import')->name('admin.beyondary.storefront.import');
        Route::post('install-preset', 'installPreset')->name('admin.beyondary.storefront.install-preset');
    });
});
