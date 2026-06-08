@php
    $slides = $theme->translate($currentLocale->code)->options['images'] ?? [['title' => '', 'link' => '', 'image' => '']];
    if (empty($slides)) {
        $slides = [['title' => '', 'link' => '', 'image' => '']];
    }
@endphp

<x-beyondary-storefront::form-panel
    :title="__('beyondary-storefront::app.forms.hero.slides_title')"
    :hint="__('beyondary-storefront::app.forms.hero.help')"
    :badge="count($slides)"
    :default-open="true"
>
    <div class="space-y-2">
        @foreach ($slides as $index => $slide)
            <x-beyondary-storefront::form-panel
                :title="__('beyondary-storefront::app.forms.hero.slide', ['n' => $index + 1])"
                :hint="$slide['title'] ?: __('beyondary-storefront::app.forms.common.empty_label')"
            >
                @if (! empty($slide['image']))
                    <input type="hidden" name="slides[{{ $index }}][existing_image]" value="{{ $slide['image'] }}">
                    <img
                        src="{{ str_starts_with($slide['image'], 'http') ? $slide['image'] : asset($slide['image']) }}"
                        alt=""
                        class="mb-3 h-28 w-auto rounded-sm object-cover"
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
            </x-beyondary-storefront::form-panel>
        @endforeach
    </div>
</x-beyondary-storefront::form-panel>
