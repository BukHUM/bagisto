@php
    $slides = $theme->translate($currentLocale->code)->options['images'] ?? [['title' => '', 'link' => '', 'image' => '']];
    if (empty($slides)) {
        $slides = [['title' => '', 'link' => '', 'image' => '']];
    }
@endphp

<p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
    @lang('beyondary-storefront::app.forms.hero.help')
</p>

<div class="space-y-6">
    @foreach ($slides as $index => $slide)
        <div class="rounded border border-gray-200 p-4 dark:border-gray-800">
            <p class="mb-3 text-sm font-semibold text-gray-800 dark:text-white">
                @lang('beyondary-storefront::app.forms.hero.slide', ['n' => $index + 1])
            </p>

            @if (! empty($slide['image']))
                <input type="hidden" name="slides[{{ $index }}][existing_image]" value="{{ $slide['image'] }}">
                <img
                    src="{{ str_starts_with($slide['image'], 'http') ? $slide['image'] : asset($slide['image']) }}"
                    alt=""
                    class="mb-3 h-24 w-auto rounded object-cover"
                >
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('beyondary-storefront::app.forms.hero.title')
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="slides[{{ $index }}][title]"
                        :value="$slide['title'] ?? ''"
                    />
                </x-admin::form.control-group>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>
                        @lang('beyondary-storefront::app.forms.hero.link')
                    </x-admin::form.control-group.label>
                    <x-admin::form.control-group.control
                        type="text"
                        name="slides[{{ $index }}][link]"
                        :value="$slide['link'] ?? ''"
                    />
                </x-admin::form.control-group>
            </div>

            <x-admin::form.control-group class="mt-3">
                <x-admin::form.control-group.label>
                    @lang('beyondary-storefront::app.forms.hero.image')
                </x-admin::form.control-group.label>
                <x-admin::form.control-group.control
                    type="file"
                    name="slides[{{ $index }}][image]"
                    accept="image/*"
                />
            </x-admin::form.control-group>
        </div>
    @endforeach
</div>
