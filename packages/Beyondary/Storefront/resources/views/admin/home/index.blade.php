<x-admin::layouts>
    <x-slot:title>
        @lang('beyondary-storefront::app.home.title')
    </x-slot>

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="max-w-2xl">
            <p class="text-xl font-bold text-admin-text">
                @lang('beyondary-storefront::app.home.title')
            </p>
            <p class="mt-1 text-sm text-admin-muted">
                @lang('beyondary-storefront::app.home.subtitle', ['channel' => $channel->name, 'theme' => $channel->theme])
            </p>
            <p class="mt-2 text-xs text-admin-muted">
                @lang('beyondary-storefront::app.home.builder_hint')
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ $storefrontUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="primary-button"
            >
                @lang('beyondary-storefront::app.home.preview')
            </a>

            @if (bouncer()->hasPermission('beyondary.storefront.export'))
                <a href="{{ $exportZipUrl }}" class="secondary-button text-sm">
                    @lang('beyondary-storefront::app.transfer.export-zip')
                </a>
            @endif
        </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-3 rounded-sm border border-admin-border bg-admin-card px-4 py-3">
        <div class="flex items-center gap-2 text-sm text-admin-text">
            <span class="font-semibold">@lang('beyondary-storefront::app.home.progress')</span>
            <span class="text-admin-muted">
                @lang('beyondary-storefront::app.home.progress_count', [
                    'configured' => $configuredCount,
                    'total' => $sections->count(),
                ])
            </span>
        </div>
        <div class="h-2 min-w-[120px] flex-1 overflow-hidden rounded-full bg-admin-surface">
            <div
                class="h-full rounded-full bg-admin-primary transition-all"
                style="width: {{ $sections->count() > 0 ? round(($configuredCount / $sections->count()) * 100) : 0 }}%"
            ></div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-12">
        {{-- Page map (Shopify / WP-style schematic) --}}
        <div class="xl:col-span-5">
            <div class="box-shadow sticky top-20 rounded-sm p-4">
                <p class="text-sm font-semibold text-admin-text">
                    @lang('beyondary-storefront::app.home.page_map_title')
                </p>
                <p class="mt-1 text-xs text-admin-muted">
                    @lang('beyondary-storefront::app.home.page_map_hint')
                </p>

                <div class="mt-4">
                    @include('beyondary-storefront::admin.home.partials.page-layout-mockup', [
                        'sections' => $sections,
                    ])
                </div>

                <div class="mt-4 space-y-1.5 text-xs text-admin-muted">
                    <p><span class="inline-block h-2 w-2 rounded-full bg-admin-primary"></span> @lang('beyondary-storefront::app.home.legend_active')</p>
                    <p><span class="inline-block h-2 w-2 rounded-full bg-admin-border"></span> @lang('beyondary-storefront::app.home.legend_missing')</p>
                </div>
            </div>
        </div>

        {{-- Section editor list --}}
        <div class="space-y-6 xl:col-span-7">
            <div>
                <div class="mb-3 flex items-center gap-2">
                    <span class="sf-zone-label">@lang('beyondary-storefront::app.home.zone_layout')</span>
                    <span class="text-xs text-admin-muted">@lang('beyondary-storefront::app.home.zone_layout_hint')</span>
                </div>
                <div class="space-y-2">
                    @foreach ($layoutSections as $section)
                        @include('beyondary-storefront::admin.home.partials.section-card', ['section' => $section])
                    @endforeach
                </div>
            </div>

            <div>
                <div class="mb-3 flex items-center gap-2">
                    <span class="sf-zone-label">@lang('beyondary-storefront::app.home.zone_homepage')</span>
                    <span class="text-xs text-admin-muted">@lang('beyondary-storefront::app.home.zone_homepage_hint')</span>
                </div>
                <div class="space-y-2">
                    @foreach ($homepageSections as $section)
                        @include('beyondary-storefront::admin.home.partials.section-card', [
                            'section' => $section,
                            'showOrder' => true,
                        ])
                    @endforeach
                </div>
            </div>

            @if (bouncer()->hasPermission('beyondary.storefront.export') || bouncer()->hasPermission('beyondary.storefront.import'))
                <div class="box-shadow rounded-sm p-4">
                    <p class="text-sm font-semibold text-admin-text">
                        @lang('beyondary-storefront::app.home.tools_title')
                    </p>
                    <p class="mt-1 text-xs text-admin-muted">
                        @lang('beyondary-storefront::app.home.tools_hint')
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @if (bouncer()->hasPermission('beyondary.storefront.export'))
                            <a href="{{ $exportJsonUrl }}" class="transparent-button text-sm">
                                @lang('beyondary-storefront::app.transfer.export-json')
                            </a>
                        @endif

                        @if (bouncer()->hasPermission('beyondary.storefront.import'))
                            <button
                                type="button"
                                class="secondary-button text-sm"
                                onclick="document.getElementById('sf-import-panel').classList.toggle('hidden')"
                            >
                                @lang('beyondary-storefront::app.transfer.import')
                            </button>
                        @endif

                        @if (bouncer()->hasPermission('beyondary.storefront.import'))
                            <form method="POST" action="{{ $installPresetUrl }}" class="inline" onsubmit="return confirm(@js(__('beyondary-storefront::app.transfer.preset-confirm')));">
                                @csrf
                                <input type="hidden" name="channel_id" value="{{ $channel->id }}" />
                                <button type="submit" class="transparent-button text-sm">
                                    @lang('beyondary-storefront::app.transfer.install-preset')
                                </button>
                            </form>
                        @endif
                    </div>

                    @if (bouncer()->hasPermission('beyondary.storefront.import'))
                        <div id="sf-import-panel" class="mt-4 hidden rounded-sm border border-admin-border bg-admin-surface p-4">
                            <p class="text-sm font-medium text-admin-text">@lang('beyondary-storefront::app.transfer.import-title')</p>
                            <p class="mt-1 text-xs text-admin-muted">@lang('beyondary-storefront::app.transfer.import-help')</p>

                            <form method="POST" action="{{ $importUrl }}" enctype="multipart/form-data" class="mt-3 space-y-3">
                                @csrf
                                <input type="hidden" name="channel_id" value="{{ $channel->id }}" />
                                <input
                                    type="file"
                                    name="import_file"
                                    accept=".zip,.json,.txt"
                                    required
                                    class="block w-full text-sm text-admin-text file:mr-3 file:rounded-sm file:border-0 file:bg-admin-primary file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-white"
                                />
                                <label class="flex items-center gap-2 text-sm text-admin-text">
                                    <input type="checkbox" name="replace_existing" value="1" checked class="rounded border-admin-border text-admin-primary" />
                                    @lang('beyondary-storefront::app.transfer.replace')
                                </label>
                                <button type="submit" class="primary-button text-sm">
                                    @lang('beyondary-storefront::app.transfer.import')
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-admin::layouts>
