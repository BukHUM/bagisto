@php
    $fieldClass = 'w-full rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30';
@endphp

@push('scripts')
    <script>
        (() => {
            const loadedFonts = new Set();

            const encodeFamily = (family) => family.trim().replace(/\s+/g, '+');

            const fallbackStack = (role) => role === 'display'
                ? 'ui-serif, Georgia, serif'
                : 'ui-sans-serif, system-ui, sans-serif';

            const loadGoogleFont = (family, weights) => new Promise((resolve) => {
                const key = `${family}:${weights}`;

                if (! family || loadedFonts.has(key)) {
                    resolve();

                    return;
                }

                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = `https://fonts.googleapis.com/css2?family=${encodeFamily(family)}:wght@${weights}&display=swap`;
                link.onload = () => resolve();
                link.onerror = () => resolve();
                document.head.appendChild(link);
                loadedFonts.add(key);
            });

            window.beyondaryAdminFontPreviewUpdate = async (select) => {
                const form = document.getElementById('beyondary-admin-theme-settings-form');

                if (! form || ! select?.matches('.admin-font-select')) {
                    return;
                }

                const role = select.dataset.fontRole;
                const family = select.value;
                const option = select.selectedOptions[0];
                const weights = option?.dataset.weights ?? '400;500;600;700';
                const preview = form.querySelector(`[data-font-preview="${role}"]`);

                if (! preview || ! family) {
                    return;
                }

                preview.style.fontFamily = `'${family}', ${fallbackStack(role)}`;

                await loadGoogleFont(family, weights);

                if (document.fonts?.load) {
                    try {
                        await document.fonts.load(`1rem "${family}"`);
                    } catch (error) {
                        // Ignore font load errors; fallback stack remains visible.
                    }
                }

                preview.style.fontFamily = `'${family}', ${fallbackStack(role)}`;
            };

            window.initBeyondaryAdminFontPreview = function () {
                const form = document.getElementById('beyondary-admin-theme-settings-form');

                if (! form || form.dataset.fontPreviewReady === '1') {
                    return;
                }

                form.dataset.fontPreviewReady = '1';

                form.querySelectorAll('.admin-font-select').forEach((select) => {
                    window.beyondaryAdminFontPreviewUpdate(select);
                });
            };

            window.addEventListener('load', () => {
                window.initBeyondaryAdminFontPreview();

                window.setTimeout(() => {
                    window.initBeyondaryAdminFontPreview();
                }, 300);
            });
        })();
    </script>
@endpush

<x-admin::layouts>
    <x-slot:title>
        @lang('beyondary-storefront::app.admin_theme.title')
    </x-slot>

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xl font-bold text-admin-text">
                @lang('beyondary-storefront::app.admin_theme.title')
            </p>
            <p class="mt-1 text-sm text-admin-muted">
                @lang('beyondary-storefront::app.admin_theme.subtitle')
            </p>
        </div>
    </div>

    <form
        method="POST"
        action="{{ route('admin.beyondary.admin-theme.update') }}"
        class="mt-6"
    >
        @csrf

        <div class="box-shadow rounded-sm p-5">
            <p class="mb-4 text-base font-semibold text-admin-text">
                @lang('beyondary-storefront::app.admin_theme.choose')
            </p>

            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($themes as $theme)
                    <label
                        @class([
                            'admin-theme-option',
                            'admin-theme-option--active' => $currentTheme === $theme['code'],
                        ])
                    >
                        <input
                            type="radio"
                            name="theme"
                            value="{{ $theme['code'] }}"
                            @checked($currentTheme === $theme['code'])
                        />

                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold text-admin-text">
                                {{ $theme['name'] }}
                            </span>

                            <span class="mt-1 block text-xs text-admin-muted">
                                <code>{{ $theme['code'] }}</code>
                                @if ($defaultTheme === $theme['code'])
                                    · @lang('beyondary-storefront::app.admin_theme.system_default')
                                @endif
                            </span>

                            @if ($theme['code'] === 'beyondary-admin')
                                <span class="mt-2 block text-xs text-admin-muted">
                                    @lang('beyondary-storefront::app.admin_theme.beyondary_hint')
                                </span>
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>

            <p class="mt-4 text-xs text-admin-muted">
                @lang('beyondary-storefront::app.admin_theme.per_user_note')
            </p>

            <div class="mt-5 flex flex-wrap items-center gap-2.5">
                <button type="submit" class="primary-button">
                    @lang('beyondary-storefront::app.admin_theme.save')
                </button>
            </div>
        </div>
    </form>

    <form
        method="POST"
        action="{{ route('admin.beyondary.admin-theme.settings.update') }}"
        class="mt-6"
        id="beyondary-admin-theme-settings-form"
    >
        @csrf

        <div class="box-shadow rounded-sm p-5">
            <p class="text-base font-semibold text-admin-text">
                @lang('beyondary-storefront::app.admin_theme.settings_title')
            </p>
            <p class="mt-1 text-sm text-admin-muted">
                @lang('beyondary-storefront::app.admin_theme.settings_subtitle')
            </p>

            <div class="mt-6 space-y-6">
                <div>
                    <p class="mb-3 text-sm font-semibold text-admin-text">
                        @lang('beyondary-storefront::app.admin_theme.typography')
                    </p>

                    <div class="admin-theme-settings-grid">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-admin-text" for="font_sans">
                                @lang('beyondary-storefront::app.admin_theme.font_sans')
                            </label>
                            <select
                                id="font_sans"
                                name="typography[font_sans]"
                                class="{{ $fieldClass }} admin-font-select"
                                data-font-role="sans"
                                onchange="window.beyondaryAdminFontPreviewUpdate?.(this)"
                            >
                                @foreach ($fontOptions['sans'] as $value => $meta)
                                    <option
                                        value="{{ $value }}"
                                        data-weights="{{ $meta['weights'] }}"
                                        @selected($settings['typography']['font_sans'] === $value)
                                    >
                                        {{ $meta['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="admin-font-preview mt-2 rounded-sm border border-admin-border bg-admin-surface px-3 py-2.5 text-sm text-admin-text"
                                data-font-preview="sans"
                                style="font-family: '{{ $settings['typography']['font_sans'] }}', sans-serif;"
                            >
                                @lang('beyondary-storefront::app.admin_theme.font_preview_sans')
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-admin-text" for="font_display">
                                @lang('beyondary-storefront::app.admin_theme.font_display')
                            </label>
                            <select
                                id="font_display"
                                name="typography[font_display]"
                                class="{{ $fieldClass }} admin-font-select"
                                data-font-role="display"
                                onchange="window.beyondaryAdminFontPreviewUpdate?.(this)"
                            >
                                @foreach ($fontOptions['display'] as $value => $meta)
                                    <option
                                        value="{{ $value }}"
                                        data-weights="{{ $meta['weights'] }}"
                                        @selected($settings['typography']['font_display'] === $value)
                                    >
                                        {{ $meta['label'] }}@if (! empty($meta['supports_thai'])) · @lang('beyondary-storefront::app.admin_theme.font_thai_badge')@endif
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="admin-font-preview mt-2 rounded-sm border border-admin-border bg-admin-surface px-3 py-2.5 text-lg text-admin-text"
                                data-font-preview="display"
                                style="font-family: '{{ $settings['typography']['font_display'] }}', serif;"
                            >
                                @lang('beyondary-storefront::app.admin_theme.font_preview_display')
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-admin-text" for="base_size">
                                @lang('beyondary-storefront::app.admin_theme.base_size')
                            </label>
                            <input
                                id="base_size"
                                type="number"
                                name="typography[base_size]"
                                value="{{ $settings['typography']['base_size'] }}"
                                min="12"
                                max="18"
                                class="{{ $fieldClass }}"
                            />
                            <p class="mt-1 text-xs text-admin-muted">
                                @lang('beyondary-storefront::app.admin_theme.base_size_help')
                            </p>
                        </div>
                    </div>

                    <p class="mt-2 text-xs text-admin-muted">
                        @lang('beyondary-storefront::app.admin_theme.font_preview_hint')
                    </p>
                </div>

                <div>
                    <p class="mb-3 text-sm font-semibold text-admin-text">
                        @lang('beyondary-storefront::app.admin_theme.colors')
                    </p>

                    <div class="admin-theme-settings-grid">
                        @foreach ([
                            'primary' => 'color_primary',
                            'primary_hover' => 'color_primary_hover',
                            'sidebar' => 'color_sidebar',
                            'surface' => 'color_surface',
                            'text' => 'color_text',
                            'muted' => 'color_muted',
                            'border' => 'color_border',
                        ] as $key => $labelKey)
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-admin-text" for="color_{{ $key }}">
                                    @lang('beyondary-storefront::app.admin_theme.'.$labelKey)
                                </label>
                                <input
                                    id="color_{{ $key }}"
                                    type="text"
                                    name="colors[{{ $key }}]"
                                    value="{{ $settings['colors'][$key] }}"
                                    pattern="^#[A-Fa-f0-9]{6}$"
                                    class="{{ $fieldClass }} admin-theme-color-input font-mono uppercase"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-sm font-semibold text-admin-text">
                        @lang('beyondary-storefront::app.admin_theme.layout')
                    </p>

                    <div class="admin-theme-settings-grid">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-admin-text" for="layout_density">
                                @lang('beyondary-storefront::app.admin_theme.density')
                            </label>
                            <select id="layout_density" name="layout[density]" class="{{ $fieldClass }}">
                                <option value="comfortable" @selected($settings['layout']['density'] === 'comfortable')>
                                    @lang('beyondary-storefront::app.admin_theme.density_comfortable')
                                </option>
                                <option value="compact" @selected($settings['layout']['density'] === 'compact')>
                                    @lang('beyondary-storefront::app.admin_theme.density_compact')
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-admin-text" for="layout_corner_radius">
                                @lang('beyondary-storefront::app.admin_theme.corner_radius')
                            </label>
                            <select id="layout_corner_radius" name="layout[corner_radius]" class="{{ $fieldClass }}">
                                <option value="sm" @selected($settings['layout']['corner_radius'] === 'sm')>
                                    @lang('beyondary-storefront::app.admin_theme.corner_sm')
                                </option>
                                <option value="md" @selected($settings['layout']['corner_radius'] === 'md')>
                                    @lang('beyondary-storefront::app.admin_theme.corner_md')
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-4 text-xs text-admin-muted">
                @lang('beyondary-storefront::app.admin_theme.settings_global_note')
            </p>

            <div class="mt-5 flex flex-wrap items-center gap-2.5">
                <button type="submit" class="primary-button">
                    @lang('beyondary-storefront::app.admin_theme.save_settings')
                </button>
            </div>
        </div>
    </form>
</x-admin::layouts>
