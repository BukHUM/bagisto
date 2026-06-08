@php
    use App\Helpers\AdminMenu;

    $sidebarMenuItems = AdminMenu::serializedItems();
    $isHelpActive = AdminMenu::isHelpActive();
@endphp

<div class="beyondary-admin-sidebar group/sidebar relative flex h-[calc(100vh-3.5rem)] w-full flex-col bg-admin-sidebar transition-all duration-300 max-lg:hidden">
    <v-beyondary-sidebar-nav
        :items='@json($sidebarMenuItems)'
        help-url="{{ route('admin.help.index') }}"
        help-label="{{ __('admin::app.components.layouts.sidebar.help') }}"
        :help-active="{{ $isHelpActive ? 'true' : 'false' }}"
    ></v-beyondary-sidebar-nav>

    <v-sidebar-collapse></v-sidebar-collapse>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-beyondary-sidebar-nav-template"
    >
        <div class="flex h-full min-h-0 flex-col">
            <div class="sidebar-scroll flex-1 overflow-y-auto overflow-x-hidden px-2 py-3">
                <nav class="flex flex-col gap-0">
                    <div
                        v-for="item in items"
                        :key="item.key"
                        class="sidebar-menu-item group/item"
                        @mouseenter="showCollapsedFlyout(item.key, $event)"
                        @mouseleave="hideCollapsedFlyout()"
                    >
                        <a
                            v-if="! item.children.length"
                            :href="item.url"
                            class="sidebar-nav-link flex w-full items-center gap-2.5 rounded-md px-2 py-2 transition-colors duration-200"
                            :class="{ 'sidebar-nav-link--active': item.active }"
                        >
                            <span :class="item.icon + ' sidebar-nav-link__icon'"></span>

                            <p class="sidebar-nav-link__label min-w-0 flex-1 truncate text-sm font-medium">
                                @{{ item.name }}
                            </p>
                        </a>

                        <div
                            v-else
                            class="sidebar-submenu-group"
                            :class="{ 'sidebar-submenu-group--expanded': isExpanded(item.key) }"
                        >
                            <button
                                type="button"
                                class="sidebar-nav-link m-0 flex w-full items-center gap-2.5 rounded-md border-0 px-2 py-2 text-left transition-colors duration-200"
                                :class="{
                                    'sidebar-nav-link--active': item.active && ! isExpanded(item.key),
                                    'sidebar-nav-link--open': isExpanded(item.key),
                                }"
                                @click="toggle(item.key)"
                            >
                                <span :class="item.icon + ' sidebar-nav-link__icon'"></span>

                                <p class="sidebar-nav-link__label min-w-0 flex-1 truncate text-sm font-medium">
                                    @{{ item.name }}
                                </p>

                                <i
                                    class="sidebar-nav-link__chevron icon-arrow-down shrink-0 text-sm transition-transform duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
                                    :class="{ 'rotate-180': isExpanded(item.key) }"
                                ></i>
                            </button>

                            <div
                                class="sidebar-submenu-panel"
                                :class="{ 'sidebar-submenu-panel--expanded': isExpanded(item.key) }"
                            >
                                <div class="sidebar-submenu-panel__inner grid gap-0.5">
                                    <a
                                        v-for="child in item.children"
                                        :key="child.key"
                                        :href="child.url"
                                        class="sidebar-submenu-link"
                                        :class="{ 'sidebar-submenu-link--active': child.active }"
                                    >
                                        <span
                                            :class="(child.icon || 'icon-list') + ' sidebar-submenu-link__icon shrink-0'"
                                            aria-hidden="true"
                                        ></span>

                                        <span class="min-w-0 truncate">
                                            @{{ child.name }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="item.children.length"
                            class="sidebar-collapsed-flyout"
                            :class="{ 'sidebar-collapsed-flyout--visible': hoveredFlyoutKey === item.key }"
                            :style="hoveredFlyoutKey === item.key ? collapsedFlyoutStyle : null"
                            @mouseenter="keepCollapsedFlyout(item.key)"
                            @mouseleave="hideCollapsedFlyout"
                        >
                            <p class="sidebar-collapsed-flyout__title">
                                @{{ item.name }}
                            </p>

                            <a
                                v-for="child in item.children"
                                :key="child.key"
                                :href="child.url"
                                class="sidebar-collapsed-flyout__link"
                                :class="{ 'sidebar-collapsed-flyout__link--active': child.active }"
                            >
                                <span
                                    :class="(child.icon || 'icon-list') + ' sidebar-collapsed-flyout__icon'"
                                    aria-hidden="true"
                                ></span>

                                <span class="min-w-0 truncate">
                                    @{{ child.name }}
                                </span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="shrink-0 px-2 pb-[52px]">
                <div class="mb-2 border-t border-white/10"></div>

                <a
                    :href="helpUrl"
                    class="sidebar-nav-link flex w-full items-center gap-2.5 rounded-md px-2 py-2 transition-colors duration-200"
                    :class="{ 'sidebar-nav-link--active': helpActive }"
                >
                    <span class="icon-information sidebar-nav-link__icon"></span>

                    <p class="sidebar-nav-link__label truncate text-sm font-medium">
                        @{{ helpLabel }}
                    </p>
                </a>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-beyondary-sidebar-nav', {
            template: '#v-beyondary-sidebar-nav-template',

            props: {
                items: {
                    type: Array,
                    required: true,
                },
                helpUrl: {
                    type: String,
                    required: true,
                },
                helpLabel: {
                    type: String,
                    required: true,
                },
                helpActive: {
                    type: Boolean,
                    default: false,
                },
            },

            data() {
                return {
                    expandedKeys: [],
                    hoveredFlyoutKey: null,
                    collapsedFlyoutStyle: null,
                    collapsedFlyoutHideTimer: null,
                    storageKey: 'beyondary_admin_sidebar_expanded',
                };
            },

            mounted() {
                this.expandedKeys = this.resolveExpandedKeys();
            },

            beforeDestroy() {
                this.clearCollapsedFlyoutHideTimer();
            },

            methods: {
                isExpanded(key) {
                    return this.expandedKeys.includes(key);
                },

                isSidebarCollapsed() {
                    return this.$root.$refs.appLayout?.classList.contains('sidebar-collapsed') ?? false;
                },

                showCollapsedFlyout(key, event) {
                    if (! this.isSidebarCollapsed()) {
                        return;
                    }

                    const menuItem = event.currentTarget;

                    if (! menuItem.querySelector('.sidebar-collapsed-flyout')) {
                        return;
                    }

                    this.clearCollapsedFlyoutHideTimer();
                    this.hoveredFlyoutKey = key;

                    this.$nextTick(() => {
                        this.positionCollapsedFlyout(menuItem);
                    });
                },

                keepCollapsedFlyout(key) {
                    this.clearCollapsedFlyoutHideTimer();
                    this.hoveredFlyoutKey = key;
                },

                hideCollapsedFlyout() {
                    this.clearCollapsedFlyoutHideTimer();

                    this.collapsedFlyoutHideTimer = setTimeout(() => {
                        this.hoveredFlyoutKey = null;
                        this.collapsedFlyoutStyle = null;
                    }, 120);
                },

                clearCollapsedFlyoutHideTimer() {
                    if (this.collapsedFlyoutHideTimer) {
                        clearTimeout(this.collapsedFlyoutHideTimer);
                        this.collapsedFlyoutHideTimer = null;
                    }
                },

                positionCollapsedFlyout(menuItem) {
                    const flyout = menuItem.querySelector('.sidebar-collapsed-flyout');

                    if (! flyout) {
                        return;
                    }

                    const menuTop = menuItem.getBoundingClientRect().top;
                    const flyoutHeight = flyout.offsetHeight;
                    const availableHeight = window.innerHeight - menuTop;
                    let topOffset = menuTop;

                    if (flyoutHeight > availableHeight) {
                        topOffset = menuTop - (flyoutHeight - availableHeight);
                    }

                    this.collapsedFlyoutStyle = {
                        top: `${Math.max(8, topOffset)}px`,
                    };
                },

                toggle(key) {
                    if (this.isSidebarCollapsed()) {
                        return;
                    }

                    if (this.expandedKeys.includes(key)) {
                        this.expandedKeys = this.expandedKeys.filter((item) => item !== key);
                    } else {
                        this.expandedKeys = [...this.expandedKeys, key];
                    }

                    this.persistExpandedKeys();
                },

                resolveExpandedKeys() {
                    const saved = this.readSavedKeys();
                    const activeBranchKeys = this.items
                        .filter((item) => item.active && item.children.length)
                        .map((item) => item.key);

                    return [...new Set([...saved, ...activeBranchKeys])];
                },

                readSavedKeys() {
                    try {
                        const raw = localStorage.getItem(this.storageKey);

                        if (! raw) {
                            return [];
                        }

                        const parsed = JSON.parse(raw);

                        return Array.isArray(parsed) ? parsed : [];
                    } catch (error) {
                        return [];
                    }
                },

                persistExpandedKeys() {
                    localStorage.setItem(this.storageKey, JSON.stringify(this.expandedKeys));
                },
            },
        });
    </script>

    <script
        type="text/x-template"
        id="v-sidebar-collapse-template"
    >
        <div
            class="absolute bottom-0 w-full cursor-pointer border-t border-white/10 bg-admin-sidebar px-2 transition-all duration-300 hover:bg-admin-sidebar-hover"
            :class="{'max-w-[70px]': isCollapsed, 'max-w-[270px]': !isCollapsed}"
            @click="toggle"
        >
            <div class="flex items-center gap-2 px-2.5 py-2.5 group-[.sidebar-collapsed]/container:justify-center group-[.sidebar-collapsed]/container:px-0">
                <span
                    class="icon-collapse text-lg text-admin-sidebar-muted transition-all"
                    :class="[isCollapsed ? 'ltr:rotate-[180deg] rtl:rotate-[0]' : 'ltr:rotate-[0] rtl:rotate-[180deg]']"
                ></span>

                <span
                    class="text-xs font-medium text-admin-sidebar-muted"
                    v-if="!isCollapsed"
                >
                    @lang('admin::app.components.layouts.sidebar.collapse')
                </span>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-sidebar-collapse', {
            template: '#v-sidebar-collapse-template',

            data() {
                return {
                    isCollapsed: {{ request()->cookie('sidebar_collapsed') ?? 0 }},
                }
            },

            methods: {
                toggle() {
                    this.isCollapsed = parseInt(this.isCollapsedCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'sidebar_collapsed=' + this.isCollapsed + '; path=/; expires=' + expiryDate.toGMTString();

                    this.$root.$refs.appLayout.classList.toggle('sidebar-collapsed');
                },

                isCollapsedCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'sidebar_collapsed') {
                            return value;
                        }
                    }

                    return 0;
                },
            },
        });
    </script>
@endpushOnce
