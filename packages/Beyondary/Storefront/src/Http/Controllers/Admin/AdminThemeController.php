<?php

namespace Beyondary\Storefront\Http\Controllers\Admin;

use Beyondary\Storefront\Services\AdminThemeService;
use Beyondary\Storefront\Services\AdminThemeSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class AdminThemeController extends Controller
{
    public function __construct(
        protected AdminThemeService $adminThemeService,
        protected AdminThemeSettingsService $adminThemeSettingsService
    ) {}

    public function index(): View
    {
        $settings = session('beyondary_admin_theme_settings')
            ?? $this->adminThemeSettingsService->get(fresh: true);

        return view('beyondary-storefront::admin.theme.index', [
            'themes' => $this->adminThemeService->availableThemes(),
            'currentTheme' => $this->adminThemeService->resolve(),
            'defaultTheme' => $this->adminThemeService->defaultThemeCode(),
            'settings' => $settings,
            'fontOptions' => $this->adminThemeSettingsService->fontOptionsForForm($settings),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'string'],
        ]);

        $theme = $validated['theme'];

        if (! $this->adminThemeService->isValid($theme)) {
            session()->flash('error', trans('beyondary-storefront::app.admin_theme.invalid'));

            return redirect()->route('admin.beyondary.admin-theme.index');
        }

        cookie()->queue(cookie(
            AdminThemeService::COOKIE_NAME,
            $theme,
            60 * 24 * 180,
            '/',
            null,
            $request->isSecure(),
            false,
            false,
            'lax'
        ));

        session()->flash('success', trans('beyondary-storefront::app.admin_theme.saved'));

        return redirect()->route('admin.beyondary.admin-theme.index');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'typography.font_sans' => ['required', 'string', 'max:64'],
            'typography.font_display' => ['required', 'string', 'max:64'],
            'typography.base_size' => ['required', 'integer', 'min:12', 'max:18'],
            'colors.primary' => ['required', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'colors.primary_hover' => ['required', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'colors.sidebar' => ['required', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'colors.surface' => ['required', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'colors.text' => ['required', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'colors.muted' => ['required', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'colors.border' => ['required', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
            'layout.density' => ['required', 'in:comfortable,compact'],
            'layout.corner_radius' => ['required', 'in:sm,md'],
        ]);

        $settings = $this->adminThemeSettingsService->save($validated);

        session()->flash('success', trans('beyondary-storefront::app.admin_theme.settings_saved'));
        session()->flash('beyondary_admin_theme_settings', $settings);

        return redirect()
            ->route('admin.beyondary.admin-theme.index')
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
            ]);
    }
}
