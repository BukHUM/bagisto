@props([
    'title',
    'hint' => null,
    'defaultOpen' => false,
    'badge' => null,
])

<details
    {{ $attributes->class(['sf-form-panel']) }}
    @if ($defaultOpen) open @endif
>
    <summary class="sf-form-panel__summary">
        <div class="sf-form-panel__head">
            <p class="sf-form-panel__title">{{ $title }}</p>
            @if ($hint)
                <p class="sf-form-panel__hint">{{ $hint }}</p>
            @endif
        </div>

        @if ($badge !== null && $badge !== '')
            <span class="sf-form-panel__badge">{{ $badge }}</span>
        @endif

        <span class="icon-arrow-down sf-form-panel__chevron" aria-hidden="true"></span>
    </summary>

    <div class="sf-form-panel__body">
        {{ $slot }}
    </div>
</details>
