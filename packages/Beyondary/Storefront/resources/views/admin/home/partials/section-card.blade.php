@props(['section', 'showOrder' => false])

<details class="sf-section-accordion">
    <summary class="sf-section-accordion__summary">
        <div class="sf-section-accordion__thumb" aria-hidden="true">
            @include('beyondary-storefront::admin.home.partials.section-preview', [
                'type' => $section['preview'],
                'compact' => true,
            ])
        </div>

        <div class="sf-section-accordion__head">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-admin-text">
                    @if ($showOrder)
                        <span class="sf-section-card__order">{{ $section['sort_order'] / 10 }}</span>
                    @endif
                    {{ $section['label'] }}
                </p>
                <p class="mt-0.5 text-xs text-admin-muted">@lang($section['short_label'])</p>
            </div>

            @if ($section['configured'])
                <span class="sf-section-card__badge sf-section-card__badge--active">
                    @lang('beyondary-storefront::app.home.active')
                </span>
            @else
                <span class="sf-section-card__badge sf-section-card__badge--missing">
                    @lang('beyondary-storefront::app.home.missing')
                </span>
            @endif
        </div>

        <span class="icon-arrow-down sf-section-accordion__chevron" aria-hidden="true"></span>

        @if (bouncer()->hasPermission('beyondary.storefront.edit'))
            <a
                href="{{ $section['editUrl'] }}"
                class="sf-section-accordion__edit"
                onclick="event.stopPropagation()"
            >
                @lang('beyondary-storefront::app.home.edit-section')
            </a>
        @endif
    </summary>

    <div class="sf-section-accordion__body">
        <p class="text-xs text-admin-muted">@lang($section['description'])</p>

        @if (bouncer()->hasPermission('beyondary.storefront.edit'))
            <a href="{{ $section['editUrl'] }}" class="sf-section-card__action">
                @lang('beyondary-storefront::app.home.edit-section')
                <span class="icon-arrow-right text-base"></span>
            </a>
        @endif
    </div>
</details>
