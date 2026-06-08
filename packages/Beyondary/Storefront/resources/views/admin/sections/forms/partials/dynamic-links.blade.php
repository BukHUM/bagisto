@props([
    'initialLinks',
    'namePrefix' => 'links',
    'minRows' => 1,
    'maxRows' => null,
    'rowLabel' => '',
    'titleLabel' => '',
    'urlLabel' => '',
    'addLabel' => '',
    'removeLabel' => '',
])

<v-beyondary-dynamic-links
    :initial-links='@json(array_values($initialLinks))'
    name-prefix="{{ $namePrefix }}"
    :min-rows="{{ (int) $minRows }}"
    @if ($maxRows) :max-rows="{{ (int) $maxRows }}" @else :max-rows="null" @endif
    row-label="{{ $rowLabel }}"
    title-label="{{ $titleLabel }}"
    url-label="{{ $urlLabel }}"
    add-label="{{ $addLabel }}"
    remove-label="{{ $removeLabel }}"
></v-beyondary-dynamic-links>

@pushOnce('scripts')
    <script type="text/x-template" id="v-beyondary-dynamic-links-template">
        <div>
            <div class="mb-4 flex items-center justify-end">
                <button
                    type="button"
                    class="secondary-button"
                    :disabled="maxRows !== null && links.length >= maxRows"
                    @click="addLink"
                >
                    @{{ addLabel }}
                </button>
            </div>

            <div class="space-y-4">
                <div
                    v-for="(link, index) in links"
                    :key="index"
                    class="rounded border border-gray-200 p-4 dark:border-gray-800"
                >
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                            @{{ rowLabel.replace(':n', index + 1) }}
                        </p>

                        <button
                            v-if="links.length > minRows"
                            type="button"
                            class="text-sm text-red-600 transition hover:underline dark:text-red-400"
                            @click="removeLink(index)"
                        >
                            @{{ removeLabel }}
                        </button>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                @{{ titleLabel }}
                            </label>
                            <input
                                type="text"
                                class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                v-model="link.title"
                                :name="`${namePrefix}[${index}][title]`"
                            >
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">
                                @{{ urlLabel }}
                            </label>
                            <input
                                type="text"
                                class="w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                v-model="link.url"
                                :name="`${namePrefix}[${index}][url]`"
                            >
                        </div>
                    </div>

                    <input
                        type="hidden"
                        :name="`${namePrefix}[${index}][sort_order]`"
                        :value="index + 1"
                    >
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-beyondary-dynamic-links', {
            template: '#v-beyondary-dynamic-links-template',

            props: {
                initialLinks: {
                    type: Array,
                    default: () => [],
                },
                namePrefix: {
                    type: String,
                    default: 'links',
                },
                minRows: {
                    type: Number,
                    default: 1,
                },
                maxRows: {
                    type: Number,
                    default: null,
                },
                rowLabel: {
                    type: String,
                    default: 'Link :n',
                },
                titleLabel: {
                    type: String,
                    default: 'Label',
                },
                urlLabel: {
                    type: String,
                    default: 'URL',
                },
                addLabel: {
                    type: String,
                    default: 'Add link',
                },
                removeLabel: {
                    type: String,
                    default: 'Remove',
                },
            },

            data() {
                const links = this.initialLinks.length
                    ? this.initialLinks.map((link, index) => ({
                        title: link.title ?? '',
                        url: link.url ?? '',
                        sort_order: link.sort_order ?? index + 1,
                    }))
                    : [{ title: '', url: '', sort_order: 1 }];

                return { links };
            },

            methods: {
                addLink() {
                    if (this.maxRows !== null && this.links.length >= this.maxRows) {
                        return;
                    }

                    this.links.push({
                        title: '',
                        url: '',
                        sort_order: this.links.length + 1,
                    });
                },

                removeLink(index) {
                    if (this.links.length <= this.minRows) {
                        return;
                    }

                    this.links.splice(index, 1);
                },
            },
        });
    </script>
@endPushOnce
