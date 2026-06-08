@php
    $beyondaryAdminSettings = app(\Beyondary\Storefront\Services\AdminThemeSettingsService::class)->get(fresh: true);
    $beyondaryAdminFontsUrl = app(\Beyondary\Storefront\Services\AdminThemeSettingsService::class)->googleFontsUrl($beyondaryAdminSettings);
    $beyondaryAdminDensity = $beyondaryAdminSettings['layout']['density'] ?? 'comfortable';
    $beyondaryAdminCorner = $beyondaryAdminSettings['layout']['corner_radius'] ?? 'sm';
@endphp

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
    crossorigin
>
<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>
<link
    rel="preload"
    as="style"
    href="{{ $beyondaryAdminFontsUrl }}"
    onload="this.onload=null;this.rel='stylesheet'"
>
<noscript>
    <link href="{{ $beyondaryAdminFontsUrl }}" rel="stylesheet" />
</noscript>

<style id="beyondary-admin-theme-vars">
    :root {
        --admin-font-sans: '{{ $beyondaryAdminSettings['typography']['font_sans'] }}', ui-sans-serif, system-ui, sans-serif;
        --admin-font-display: '{{ $beyondaryAdminSettings['typography']['font_display'] }}', ui-serif, Georgia, serif;
        --admin-base-size: {{ (int) $beyondaryAdminSettings['typography']['base_size'] }}px;
        --admin-primary: {{ $beyondaryAdminSettings['colors']['primary'] }};
        --admin-primary-hover: {{ $beyondaryAdminSettings['colors']['primary_hover'] }};
        --admin-sidebar: {{ $beyondaryAdminSettings['colors']['sidebar'] }};
        --admin-surface: {{ $beyondaryAdminSettings['colors']['surface'] }};
        --admin-text: {{ $beyondaryAdminSettings['colors']['text'] }};
        --admin-muted: {{ $beyondaryAdminSettings['colors']['muted'] }};
        --admin-text-muted: {{ $beyondaryAdminSettings['colors']['muted'] }};
        --admin-border: {{ $beyondaryAdminSettings['colors']['border'] }};
        --admin-card: #FFFFFF;
        --admin-radius: {{ $beyondaryAdminCorner === 'md' ? '0.375rem' : '0.125rem' }};
        --admin-density-scale: {{ $beyondaryAdminDensity === 'compact' ? '0.92' : '1' }};
    }
</style>
