@props(['sections'])

<div class="sf-page-mockup" role="list">
    <div class="sf-page-mockup__chrome">
        <span></span><span></span><span></span>
        <span class="sf-page-mockup__url">beyondary.store</span>
    </div>

    <div class="sf-page-mockup__viewport">
        @foreach ($sections as $section)
            @php
                $zoneClass = 'sf-page-mockup__zone--' . $section['preview'];
            @endphp

            @if (bouncer()->hasPermission('beyondary.storefront.edit'))
                <a
                    href="{{ $section['editUrl'] }}"
                    @class([
                        'sf-page-mockup__zone group',
                        $zoneClass,
                        'sf-page-mockup__zone--active' => $section['configured'],
                    ])
                    role="listitem"
                    title="{{ $section['label'] }}"
                >
            @else
                <div
                    @class([
                        'sf-page-mockup__zone',
                        $zoneClass,
                        'sf-page-mockup__zone--active' => $section['configured'],
                    ])
                    role="listitem"
                >
            @endif
                    <div class="sf-page-mockup__art" aria-hidden="true">
                        @include('beyondary-storefront::admin.home.partials.section-preview', [
                            'type' => $section['preview'],
                        ])
                    </div>

                    <div class="sf-page-mockup__overlay">
                        <span class="sf-page-mockup__label">@lang($section['short_label'])</span>
                        @if (bouncer()->hasPermission('beyondary.storefront.edit'))
                            <span class="sf-page-mockup__hint">@lang('beyondary-storefront::app.home.map_click_edit')</span>
                        @endif
                    </div>
            @if (bouncer()->hasPermission('beyondary.storefront.edit'))
                </a>
            @else
                </div>
            @endif
        @endforeach
    </div>
</div>
