@php
    $options = $theme->translate($currentLocale->code)->options ?? [];
    $filters = $options['filters'] ?? [];
@endphp

<x-admin::form.control-group>
    <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.categories.title')</x-admin::form.control-group.label>
    <x-admin::form.control-group.control
        type="text"
        name="title"
        rules="required"
        :value="$options['title'] ?? __('beyondary.categories.title', [], $currentLocale->code)"
    />
</x-admin::form.control-group>

<div class="mt-4 grid gap-4 md:grid-cols-2">
    <x-admin::form.control-group>
        <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.common.sort')</x-admin::form.control-group.label>
        <select name="sort" class="custom-select flex min-h-[39px] w-full rounded-md border bg-white px-3 py-1.5 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            @foreach (['asc' => 'A-Z', 'desc' => 'Z-A'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['sort'] ?? 'asc') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </x-admin::form.control-group>

    <x-admin::form.control-group>
        <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.common.limit')</x-admin::form.control-group.label>
        <x-admin::form.control-group.control
            type="number"
            name="limit"
            rules="required"
            :value="$filters['limit'] ?? 4"
            min="1"
            max="20"
        />
    </x-admin::form.control-group>
</div>
