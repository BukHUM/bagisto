<x-admin::layouts>
    <x-slot:title>
        @lang('beyondary-storefront::app.home.title')
    </x-slot>

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('beyondary-storefront::app.home.title')
            </p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                @lang('beyondary-storefront::app.home.subtitle', ['channel' => $channel->name, 'theme' => $channel->theme])
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ $storefrontUrl }}" target="_blank" rel="noopener noreferrer" class="secondary-button">
                @lang('beyondary-storefront::app.home.preview')
            </a>

            @if (bouncer()->hasPermission('beyondary.storefront.export'))
                <a href="{{ $exportZipUrl }}" class="secondary-button">
                    @lang('beyondary-storefront::app.transfer.export-zip')
                </a>
                <a href="{{ $exportJsonUrl }}" class="transparent-button text-sm">
                    @lang('beyondary-storefront::app.transfer.export-json')
                </a>
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($sections as $section)
            <div class="box-shadow rounded bg-white p-5 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-base font-semibold text-gray-800 dark:text-white">{{ $section['label'] }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">@lang($section['description'])</p>
                    </div>

                    @if ($section['configured'])
                        <span class="rounded bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                            @lang('beyondary-storefront::app.home.active')
                        </span>
                    @else
                        <span class="rounded bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                            @lang('beyondary-storefront::app.home.missing')
                        </span>
                    @endif
                </div>

                <div class="mt-4">
                    @if (bouncer()->hasPermission('beyondary.storefront.edit'))
                        <a href="{{ $section['editUrl'] }}" class="primary-button text-sm">
                            @lang('beyondary-storefront::app.home.edit-section')
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if (bouncer()->hasPermission('beyondary.storefront.import'))
        <div class="mt-8 grid gap-4 lg:grid-cols-2">
            <div class="box-shadow rounded bg-white p-5 dark:bg-gray-900">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('beyondary-storefront::app.transfer.preset-title')
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    @lang('beyondary-storefront::app.transfer.preset-help')
                </p>

                <form action="{{ route('admin.beyondary.storefront.install-preset') }}" method="POST" class="mt-4 flex flex-wrap items-end gap-4">
                    @csrf
                    <div class="min-w-[200px]">
                        <label class="mb-1 block text-sm text-gray-600 dark:text-gray-300">@lang('beyondary-storefront::app.transfer.channel')</label>
                        <select name="channel_id" class="custom-select w-full rounded-md border px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900">
                            @foreach ($channels as $ch)
                                <option value="{{ $ch->id }}" @selected($ch->id === $channel->id)>{{ $ch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <input type="checkbox" name="replace_existing" value="1" checked>
                        @lang('beyondary-storefront::app.transfer.replace')
                    </label>
                    <button type="submit" class="primary-button">@lang('beyondary-storefront::app.transfer.install-preset')</button>
                </form>
            </div>

            <div class="box-shadow rounded bg-white p-5 dark:bg-gray-900">
                <p class="text-base font-semibold text-gray-800 dark:text-white">
                    @lang('beyondary-storefront::app.transfer.import-title')
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    @lang('beyondary-storefront::app.transfer.import-help')
                </p>

                <form action="{{ route('admin.beyondary.storefront.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf

                    <div>
                        <label class="mb-1 block text-sm text-gray-600 dark:text-gray-300">@lang('beyondary-storefront::app.transfer.channel')</label>
                        <select name="channel_id" class="custom-select w-full rounded-md border px-3 py-2 text-sm dark:border-gray-800 dark:bg-gray-900">
                            @foreach ($channels as $ch)
                                <option value="{{ $ch->id }}" @selected($ch->id === $channel->id)>{{ $ch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm text-gray-600 dark:text-gray-300">@lang('beyondary-storefront::app.transfer.file')</label>
                        <input type="file" name="import_file" accept=".zip,.json,application/json,application/zip" required class="w-full text-sm">
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <input type="checkbox" name="replace_existing" value="1" checked>
                        @lang('beyondary-storefront::app.transfer.replace')
                    </label>

                    <button type="submit" class="secondary-button">@lang('beyondary-storefront::app.transfer.import')</button>
                </form>
            </div>
        </div>
    @endif

    <p class="mt-6 text-xs text-gray-500 dark:text-gray-400">
        @lang('beyondary-storefront::app.home.footer-note')
    </p>
</x-admin::layouts>
