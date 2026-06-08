@php
    $options = $theme->translate($currentLocale->code)?->options ?? [];
    $defaults = app(\Beyondary\Storefront\Services\HomeSectionService::class)
        ->defaultNavigationFields($currentLocale->code);
    $links = $options['links'] ?? $defaults['links'];
@endphp

<x-admin::form.control-group>
    <x-admin::form.control-group.label class="required">
        @lang('beyondary-storefront::app.forms.menu.announcement')
    </x-admin::form.control-group.label>
    <x-admin::form.control-group.control
        type="text"
        name="announcement"
        rules="required"
        :value="$options['announcement'] ?? $defaults['announcement']"
    />
</x-admin::form.control-group>

<p class="mb-3 mt-6 text-sm text-gray-600 dark:text-gray-400">
    @lang('beyondary-storefront::app.forms.menu.help')
</p>

@include('beyondary-storefront::admin.sections.forms.partials.dynamic-links', [
    'initialLinks' => $links,
    'namePrefix' => 'links',
    'minRows' => 1,
    'maxRows' => 8,
    'rowLabel' => __('beyondary-storefront::app.forms.menu.link', ['n' => ':n']),
    'titleLabel' => __('beyondary-storefront::app.forms.menu.title'),
    'urlLabel' => __('beyondary-storefront::app.forms.menu.url'),
    'addLabel' => __('beyondary-storefront::app.forms.common.add_link'),
    'removeLabel' => __('beyondary-storefront::app.forms.common.remove_link'),
])
