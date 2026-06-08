<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>

<head>
    {!! view_render_event('bagisto.admin.layout.head.before') !!}

    <title>{{ $title ?? '' }}</title>

    <meta charset="UTF-8">

    <meta
        http-equiv="X-UA-Compatible"
        content="IE=edge"
    >
    <meta
        http-equiv="content-language"
        content="{{ app()->getLocale() }}"
    >
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="base-url"
        content="{{ url()->to('/') }}"
    >
    <meta
        name="currency"
        content="{{ core()->getBaseCurrency()->toJson() }}"
    >
    <meta
        name="generator"
        content="Bagisto"
    >

    @stack('meta')

    @bagistoVite(['assets/css/app.css', 'assets/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        rel="preload"
        as="style"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Prompt:wght@300;400;500;600;700&display=swap"
        onload="this.onload=null;this.rel='stylesheet'"
    >
    <noscript>
        <link
            href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Prompt:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />
    </noscript>

    <link
        rel="preload"
        as="image"
        href="{{ bagisto_asset('images/logo.svg') }}"
    >

    @if ($favicon = core()->getConfigData('general.design.admin_logo.favicon'))
        <link
            type="image/x-icon"
            href="{{ Storage::url($favicon) }}"
            rel="shortcut icon"
            sizes="16x16"
        >
    @else
        <link
            type="image/x-icon"
            href="{{ bagisto_asset('images/favicon.ico') }}"
            rel="shortcut icon"
            sizes="16x16"
        />
    @endif

    @stack('styles')

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    {!! view_render_event('bagisto.admin.layout.head.after') !!}
</head>

<body class="h-full bg-admin-surface">
    {!! view_render_event('bagisto.admin.layout.body.before') !!}

    <div
        id="app"
        class="h-full"
    >
        <x-admin::flash-group />

        <x-admin::modal.confirm />

        {!! view_render_event('bagisto.admin.layout.content.before') !!}

        <div
            class="group/container {{ request()->cookie('sidebar_collapsed') ?? 0 ? 'sidebar-collapsed' : 'sidebar-not-collapsed' }}"
            ref="appLayout"
        >
        <x-admin::layouts.header />

        <div class="flex flex-col lg:flex-row gap-0">
            <div class="lg:fixed lg:top-14 lg:left-0 lg:z-[1000] w-full overflow-visible lg:w-[270px] rtl:lg:right-0 rtl:lg:left-auto group-[.sidebar-collapsed]/container:lg:w-[70px]">
                <x-admin::layouts.sidebar />
            </div>

            <div class="flex min-h-[calc(100vh-3.5rem)] max-w-full flex-1 flex-col bg-admin-surface transition-all duration-300 pt-3 px-2 sm:px-4 lg:pt-4 lg:px-4 lg:ltr:ml-[270px] lg:group-[.sidebar-collapsed]/container:ltr:ml-[70px] lg:rtl:mr-[270px] lg:group-[.sidebar-collapsed]/container:rtl:mr-[70px]">
                <div class="pb-4 lg:pb-6">
                    @if (! request()->routeIs('admin.configuration.index'))
                        <div class="overflow-x-auto">
                            <x-admin::layouts.tabs />
                        </div>
                    @endif

                    <div class="w-full overflow-x-hidden">
                        {{ $slot }}
                    </div>
                </div>

                <div class="mt-auto">
                    <div class="border-t border-admin-border bg-admin-card py-2 text-center text-xs text-admin-muted sm:text-sm">
                        @lang('admin::app.components.layouts.powered-by.description', [
                            'bagisto' => '<a class="text-admin-primary hover:underline" href="https://bagisto.com/en/">Bagisto</a>',
                            'webkul' => '<a class="text-admin-primary hover:underline" href="https://webkul.com/">Webkul</a>',
                        ])
                    </div>
                </div>
            </div>
        </div>
        </div>

        {!! view_render_event('bagisto.admin.layout.content.after') !!}
    </div>

    {!! view_render_event('bagisto.admin.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('bagisto.admin.layout.vue-app-mount.before') !!}

    <script>
        window.addEventListener("load", function(event) {
            app.mount("#app");
        });
    </script>

    {!! view_render_event('bagisto.admin.layout.vue-app-mount.after') !!}
</body>

</html>
