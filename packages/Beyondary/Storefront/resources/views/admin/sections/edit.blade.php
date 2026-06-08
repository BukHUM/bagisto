<x-admin::layouts>
    <x-slot:title>
        {{ $sectionMeta['label'] }} — @lang('beyondary-storefront::app.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.beyondary.storefront.sections.update', $section)"
        enctype="multipart/form-data"
    >
        <div class="sf-section-edit">
            <div class="sf-section-edit__header">
                <div class="flex min-w-0 flex-1 items-start gap-4">
                    <div class="sf-section-edit__preview" aria-hidden="true">
                        @include('beyondary-storefront::admin.home.partials.section-preview', [
                            'type' => $sectionMeta['preview'],
                        ])
                    </div>

                    <div class="min-w-0">
                        <a
                            href="{{ $backUrl }}"
                            class="sf-section-edit__back"
                        >
                            <span class="icon-arrow-left text-sm"></span>
                            @lang('beyondary-storefront::app.edit.back_to_builder')
                        </a>

                        <p class="mt-2 text-xl font-bold text-admin-text">
                            {{ $sectionMeta['label'] }}
                        </p>

                        <p class="mt-1 text-sm text-admin-muted">
                            @lang('beyondary-storefront::app.sections.'.$section)
                        </p>

                        <p class="mt-1 text-xs text-admin-muted">
                            @lang('beyondary-storefront::app.edit.subtitle', ['channel' => $channel->name])
                        </p>
                    </div>
                </div>

                <div class="flex shrink-0 flex-wrap items-center gap-2">
                    <a
                        href="{{ $storefrontUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="transparent-button text-sm"
                    >
                        @lang('beyondary-storefront::app.home.preview')
                    </a>

                    <button type="submit" class="primary-button">
                        @lang('beyondary-storefront::app.edit.save')
                    </button>
                </div>
            </div>

            <div class="sf-section-edit__toolbar">
                <div class="sf-locale-tabs">
                    @foreach ($channel->locales->sortBy('name') as $locale)
                        <a
                            href="?{{ http_build_query(['channel' => $channel->code, 'locale' => $locale->code]) }}"
                            @class([
                                'sf-locale-tabs__item',
                                'sf-locale-tabs__item--active' => $locale->code === $currentLocale->code,
                            ])
                        >
                            {{ $locale->name }}
                        </a>
                    @endforeach
                </div>

                <input type="hidden" name="locale" value="{{ $currentLocale->code }}">

                <label class="sf-section-edit__status">
                    <input
                        type="checkbox"
                        name="status"
                        value="on"
                        class="rounded border-admin-border text-admin-primary"
                        @checked($theme->status)
                    >
                    @if (in_array($section, ['menu', 'footer'], true))
                        @lang('beyondary-storefront::app.edit.enabled-layout')
                    @else
                        @lang('beyondary-storefront::app.edit.enabled')
                    @endif
                </label>
            </div>

            <div class="sf-section-edit__layout">
                <aside class="sf-section-edit__aside">
                    <div class="box-shadow sticky top-20 rounded-sm p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-admin-muted">
                            @lang('beyondary-storefront::app.edit.preview_label')
                        </p>

                        <div class="sf-section-edit__aside-preview mt-3" aria-hidden="true">
                            @include('beyondary-storefront::admin.home.partials.section-preview', [
                                'type' => $sectionMeta['preview'],
                            ])
                        </div>

                        <p class="mt-3 text-xs text-admin-muted">
                            @lang('beyondary-storefront::app.home.section_short.'.$section)
                        </p>

                        <p class="mt-2 text-xs text-admin-muted">
                            @lang('beyondary-storefront::app.edit.panel_hint')
                        </p>
                    </div>
                </aside>

                <div class="sf-section-edit__form space-y-3">
                    @include($formPartial, [
                        'theme' => $theme,
                        'currentLocale' => $currentLocale,
                        'section' => $section,
                    ])
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
