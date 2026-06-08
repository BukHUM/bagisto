<x-admin::layouts>
    <x-slot:title>
        {{ $sectionMeta['label'] }} — @lang('beyondary-storefront::app.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.beyondary.storefront.sections.update', $section)"
        enctype="multipart/form-data"
    >
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    {{ $sectionMeta['label'] }}
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    @lang('beyondary-storefront::app.edit.subtitle', ['channel' => $channel->name])
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ $backUrl }}" class="transparent-button">
                    @lang('beyondary-storefront::app.edit.back')
                </a>
                <button type="submit" class="primary-button">
                    @lang('beyondary-storefront::app.edit.save')
                </button>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">
            @foreach ($channel->locales->sortBy('name') as $locale)
                <a
                    href="?{{ http_build_query(['channel' => $channel->code, 'locale' => $locale->code]) }}"
                    class="rounded px-3 py-1.5 text-sm {{ $locale->code === $currentLocale->code ? 'bg-gray-200 dark:bg-gray-800 font-semibold' : 'hover:bg-gray-100 dark:hover:bg-gray-900' }}"
                >
                    {{ $locale->name }}
                </a>
            @endforeach

            <input type="hidden" name="locale" value="{{ $currentLocale->code }}">

            <label class="ml-auto flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <input type="checkbox" name="status" value="on" @checked($theme->status)>
                @if (in_array($section, ['menu', 'footer'], true))
                    @lang('beyondary-storefront::app.edit.enabled-layout')
                @else
                    @lang('beyondary-storefront::app.edit.enabled')
                @endif
            </label>
        </div>

        <div class="mt-6 box-shadow rounded bg-white p-5 dark:bg-gray-900">
            @include($formPartial, [
                'theme' => $theme,
                'currentLocale' => $currentLocale,
            ])
        </div>
    </x-admin::form>
</x-admin::layouts>
