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
    'emptyLabel' => '',
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
    empty-label="{{ $emptyLabel ?: __('beyondary-storefront::app.forms.common.empty_label') }}"
    items-label="{{ __('beyondary-storefront::app.forms.common.items_count') }}"
></v-beyondary-dynamic-links>

@pushOnce('scripts')
    <script type="text/x-template" id="v-beyondary-dynamic-links-template">
        <div class="sf-dynamic-links">
            <div class="sf-dynamic-links__toolbar">
                <p class="sf-dynamic-links__count">
                    @{{ itemsLabel.replace(':count', links.length) }}
                </p>

                <button
                    type="button"
                    class="secondary-button text-sm"
                    :disabled="maxRows !== null && links.length >= maxRows"
                    @click="addLink"
                >
                    @{{ addLabel }}
                </button>
            </div>

            <div class="space-y-2">
                <details
                    v-for="(link, index) in links"
                    :key="index"
                    class="sf-form-panel sf-dynamic-link"
                    :open="link._expanded || undefined"
                >
                    <summary class="sf-form-panel__summary">
                        <div class="sf-form-panel__head">
                            <p class="sf-form-panel__title">
                                @{{ rowLabel.replace(':n', index + 1) }}
                            </p>
                            <p class="sf-form-panel__hint sf-dynamic-link__preview">
                                @{{ link.title || emptyLabel }}
                            </p>
                        </div>

                        <button
                            v-if="links.length > minRows"
                            type="button"
                            class="sf-dynamic-link__remove"
                            @click.stop="removeLink(index)"
                        >
                            @{{ removeLabel }}
                        </button>

                        <span class="icon-arrow-down sf-form-panel__chevron" aria-hidden="true"></span>
                    </summary>

                    <div class="sf-form-panel__body">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-admin-muted">
                                    @{{ titleLabel }}
                                </label>
                                <input
                                    type="text"
                                    class="w-full rounded-sm border border-admin-border bg-white px-3 py-2 text-sm text-admin-text transition hover:border-admin-primary/40 focus:border-admin-primary focus:outline-none dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                    v-model="link.title"
                                    :name="`${namePrefix}[${index}][title]`"
                                >
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-medium text-admin-muted">
                                    @{{ urlLabel }}
                                </label>
                                <input
                                    type="text"
                                    class="w-full rounded-sm border border-admin-border bg-white px-3 py-2 text-sm text-admin-text transition hover:border-admin-primary/40 focus:border-admin-primary focus:outline-none dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                                    v-model="link.url"
                                    :name="`${namePrefix}[${index}][url]`"
                                    placeholder="https://"
                                >
                            </div>
                        </div>

                        <input
                            type="hidden"
                            :name="`${namePrefix}[${index}][sort_order]`"
                            :value="index + 1"
                        >
                    </div>
                </details>
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
                emptyLabel: {
                    type: String,
                    default: 'Not set',
                },
                itemsLabel: {
                    type: String,
                    default: ':count items',
                },
            },

            data() {
                const links = this.initialLinks.length
                    ? this.initialLinks.map((link, index) => ({
                        title: link.title ?? '',
                        url: link.url ?? '',
                        sort_order: link.sort_order ?? index + 1,
                        _expanded: false,
                    }))
                    : [{ title: '', url: '', sort_order: 1, _expanded: false }];

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
                        _expanded: true,
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
