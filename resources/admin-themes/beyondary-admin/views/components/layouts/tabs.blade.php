@php
    $tabs = menu()->getCurrentActiveMenu('admin')?->getChildren();
@endphp

@if (
    $tabs
    && $tabs->isNotEmpty()
)
    <div class="tabs">
        <div class="mb-4 flex gap-4 border-b-2 border-admin-border pt-2 max-sm:hidden">
            @foreach ($tabs as $tab)
                <a href="{{ $tab->getUrl() }}">
                    <div class="{{ $tab->isActive() ? '-mb-px border-admin-primary border-b-2 transition text-admin-text' : 'text-admin-muted' }} cursor-pointer px-2.5 pb-3.5 text-base font-medium">
                        {{ $tab->getName() }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
