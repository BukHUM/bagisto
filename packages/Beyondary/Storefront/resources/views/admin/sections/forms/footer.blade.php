@php
    $options = $theme->translate($currentLocale->code)?->options ?? [];
    $defaults = app(\Beyondary\Storefront\Services\HomeSectionService::class)
        ->defaultFooterFields($currentLocale->code);
    $social = $options['social'] ?? $defaults['social'];
    $supportLinks = $options['column_2'] ?? $defaults['column_2'];

    if (empty($supportLinks)) {
        $supportLinks = [['title' => '', 'url' => '', 'sort_order' => 1]];
    }
@endphp

<x-admin::form.control-group>
    <x-admin::form.control-group.label class="required">
        @lang('beyondary-storefront::app.forms.footer.about')
    </x-admin::form.control-group.label>
    <x-admin::form.control-group.control
        type="textarea"
        name="about"
        rules="required"
        :value="$options['about'] ?? $defaults['about']"
        rows="4"
    />
</x-admin::form.control-group>

<div class="mt-6 grid gap-4 md:grid-cols-3">
    <x-admin::form.control-group>
        <x-admin::form.control-group.label>@lang('beyondary-storefront::app.forms.footer.facebook')</x-admin::form.control-group.label>
        <x-admin::form.control-group.control type="text" name="social_facebook" :value="$social['facebook'] ?? '#'" />
    </x-admin::form.control-group>
    <x-admin::form.control-group>
        <x-admin::form.control-group.label>@lang('beyondary-storefront::app.forms.footer.instagram')</x-admin::form.control-group.label>
        <x-admin::form.control-group.control type="text" name="social_instagram" :value="$social['instagram'] ?? '#'" />
    </x-admin::form.control-group>
    <x-admin::form.control-group>
        <x-admin::form.control-group.label>@lang('beyondary-storefront::app.forms.footer.pinterest')</x-admin::form.control-group.label>
        <x-admin::form.control-group.control type="text" name="social_pinterest" :value="$social['pinterest'] ?? '#'" />
    </x-admin::form.control-group>
</div>

<p class="mb-3 mt-8 text-sm font-semibold text-gray-800 dark:text-white">
    @lang('beyondary-storefront::app.forms.footer.support_links')
</p>
<p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
    @lang('beyondary-storefront::app.forms.footer.support_help')
</p>

@include('beyondary-storefront::admin.sections.forms.partials.dynamic-links', [
    'initialLinks' => $supportLinks,
    'namePrefix' => 'links',
    'minRows' => 1,
    'rowLabel' => __('beyondary-storefront::app.forms.footer.link_row'),
    'titleLabel' => __('beyondary-storefront::app.forms.footer.link_title'),
    'urlLabel' => __('beyondary-storefront::app.forms.footer.link_url'),
    'addLabel' => __('beyondary-storefront::app.forms.common.add_link'),
    'removeLabel' => __('beyondary-storefront::app.forms.common.remove_link'),
])
