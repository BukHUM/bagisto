@php
    $options = $theme->translate($currentLocale->code)->options ?? [];
    $filters = $options['filters'] ?? [];
    $sortOptions = [
        'created_at-desc' => 'Newest',
        'created_at-asc' => 'Oldest',
        'price-desc' => 'Price high → low',
        'price-asc' => 'Price low → high',
        'name-asc' => 'Name A-Z',
    ];
@endphp

<x-beyondary-storefront::form-panel
    :title="__('beyondary-storefront::app.forms.products.panel_title')"
    :hint="__('beyondary-storefront::app.sections.products')"
    :default-open="true"
>
    <x-admin::form.control-group>
        <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.products.title')</x-admin::form.control-group.label>
        <x-admin::form.control-group.control
            type="text"
            name="title"
            rules="required"
            :value="$options['title'] ?? __('beyondary.home.featured_title', [], $currentLocale->code)"
        />
    </x-admin::form.control-group>

    <div class="mt-4 grid gap-4 md:grid-cols-3">
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.common.sort')</x-admin::form.control-group.label>
            <select name="sort" class="custom-select flex min-h-[39px] w-full rounded-sm border border-admin-border bg-white px-3 py-1.5 text-sm text-admin-text dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                @foreach ($sortOptions as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['sort'] ?? 'created_at-desc') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.common.limit')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="number"
                name="limit"
                rules="required"
                :value="$filters['limit'] ?? 8"
                min="1"
                max="20"
            />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label>@lang('beyondary-storefront::app.forms.products.category_id')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control
                type="number"
                name="category_id"
                :value="$filters['category_id'] ?? ''"
                min="1"
            />
            <p class="mt-1 text-xs text-admin-muted">@lang('beyondary-storefront::app.forms.products.category_help')</p>
        </x-admin::form.control-group>
    </div>
</x-beyondary-storefront::form-panel>
