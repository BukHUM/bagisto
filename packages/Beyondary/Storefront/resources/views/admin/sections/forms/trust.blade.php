@php
    $services = $theme->translate($currentLocale->code)->options['services'] ?? [];
    $icons = [
        'icon-truck' => 'Shipping',
        'icon-product' => 'Product',
        'icon-dollar-sign' => 'Payment',
        'icon-support' => 'Support',
    ];

    if (count($services) < 3) {
        $services = array_pad($services, 3, ['title' => '', 'description' => '', 'service_icon' => 'icon-truck']);
    }
@endphp

<p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
    @lang('beyondary-storefront::app.forms.trust.help')
</p>

<div class="space-y-4">
    @foreach (array_slice($services, 0, 3) as $index => $service)
        <div class="rounded border border-gray-200 p-4 dark:border-gray-800">
            <p class="mb-3 text-sm font-semibold">@lang('beyondary-storefront::app.forms.trust.badge', ['n' => $index + 1])</p>

            <div class="grid gap-4 md:grid-cols-3">
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.trust.title')</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="services[{{ $index }}][title]"
                        rules="required"
                        :value="$service['title'] ?? ''"
                    />
                </x-admin::form.control-group>

                <x-admin::form.control-group class="md:col-span-2">
                    <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.trust.description')</x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="services[{{ $index }}][description]"
                        rules="required"
                        :value="$service['description'] ?? ''"
                    />
                </x-admin::form.control-group>
            </div>

            <x-admin::form.control-group class="mt-3">
                <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.trust.icon')</x-admin::form.control-group.label>
                <select
                    name="services[{{ $index }}][service_icon]"
                    class="custom-select flex min-h-[39px] w-full max-w-xs rounded-md border bg-white px-3 py-1.5 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                >
                    @foreach ($icons as $value => $label)
                        <option value="{{ $value }}" @selected(($service['service_icon'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </x-admin::form.control-group>
        </div>
    @endforeach
</div>
