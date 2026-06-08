@php
    $fields = app(\Beyondary\Storefront\Services\HomeSectionService::class)
        ->parseOurStoryFields($theme, $currentLocale->code);
@endphp

<x-beyondary-storefront::form-panel
    :title="__('beyondary-storefront::app.forms.our_story.copy_title')"
    :default-open="true"
>
    <div class="grid gap-4 md:grid-cols-2">
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.our_story.title')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="title" rules="required" :value="$fields['title']" />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.our_story.highlight')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="title_highlight" rules="required" :value="$fields['title_highlight']" />
        </x-admin::form.control-group>
    </div>

    <x-admin::form.control-group class="mt-4">
        <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.our_story.p1')</x-admin::form.control-group.label>
        <x-admin::form.control-group.control type="textarea" name="p1" rules="required" :value="$fields['p1']" rows="3" />
    </x-admin::form.control-group>

    <x-admin::form.control-group class="mt-4">
        <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.our_story.p2')</x-admin::form.control-group.label>
        <x-admin::form.control-group.control type="textarea" name="p2" rules="required" :value="$fields['p2']" rows="3" />
    </x-admin::form.control-group>
</x-beyondary-storefront::form-panel>

<x-beyondary-storefront::form-panel :title="__('beyondary-storefront::app.forms.our_story.cta_title')">
    <div class="grid gap-4 md:grid-cols-2">
        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.our_story.cta')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="cta" rules="required" :value="$fields['cta']" />
        </x-admin::form.control-group>

        <x-admin::form.control-group>
            <x-admin::form.control-group.label class="required">@lang('beyondary-storefront::app.forms.our_story.cta_link')</x-admin::form.control-group.label>
            <x-admin::form.control-group.control type="text" name="cta_link" rules="required" :value="$fields['cta_link']" />
        </x-admin::form.control-group>
    </div>
</x-beyondary-storefront::form-panel>

<x-beyondary-storefront::form-panel :title="__('beyondary-storefront::app.forms.our_story.image')">
    @if (! empty($fields['image_url']))
        <img src="{{ $fields['image_url'] }}" alt="" class="mb-3 h-36 w-auto rounded-sm object-cover">
        <input type="hidden" name="image_url" value="{{ $fields['image_url'] }}">
    @endif
    <x-admin::form.control-group>
        <x-admin::form.control-group.label>@lang('beyondary-storefront::app.forms.our_story.image_upload')</x-admin::form.control-group.label>
        <x-admin::form.control-group.control type="file" name="image" accept="image/*" />
    </x-admin::form.control-group>
</x-beyondary-storefront::form-panel>
