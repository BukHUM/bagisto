@php
    use App\Helpers\AdminMenu;

    $admin = auth()->guard('admin')->user();
    $adminLogo = core()->getConfigData('general.design.admin_logo.logo_image');
    $sidebarMenuItems = AdminMenu::serializedItems();
    $isHelpActive = AdminMenu::isHelpActive();
@endphp

<header class="beyondary-admin-header sticky top-0 z-[10001] flex h-14 items-center gap-2 border-b border-admin-border bg-admin-card px-3 sm:gap-3 lg:grid lg:grid-cols-[270px_1fr_auto] lg:items-center lg:gap-0 lg:px-0 group-[.sidebar-collapsed]/container:lg:grid-cols-[70px_1fr_auto]">
    <!-- โซนโลโก้ — กว้างเท่า sidebar พื้นขาวให้โลโก้มองเห็นชัด -->
    <div class="admin-header-logo-zone flex min-w-0 flex-1 items-center gap-2 px-0 lg:col-start-1 lg:h-14 lg:w-[270px] lg:flex-none lg:shrink-0 lg:border-r lg:border-admin-border lg:bg-admin-card lg:px-4 group-[.sidebar-collapsed]/container:lg:w-[70px] group-[.sidebar-collapsed]/container:lg:justify-center group-[.sidebar-collapsed]/container:lg:px-2">
        <button
            type="button"
            class="admin-header-action shrink-0 lg:hidden"
            aria-label="@lang('admin::app.components.layouts.header.mega-search.title')"
            @click="$refs.sidebarMenuDrawer.open()"
        >
            <i class="icon-menu text-xl"></i>
        </button>

        <a
            href="{{ route('admin.dashboard.index') }}"
            class="flex min-h-[2rem] min-w-0 flex-1 items-center lg:min-h-0 lg:flex-none"
        >
            @if ($adminLogo)
                <img
                    class="h-8 w-auto max-w-[170px] object-contain group-[.sidebar-collapsed]/container:!hidden"
                    src="{{ Storage::url($adminLogo) }}"
                    alt="{{ config('app.name') }}"
                />
            @else
                <img
                    src="{{ bagisto_asset('images/logo.svg') }}"
                    class="h-8 w-auto max-w-[170px] object-contain group-[.sidebar-collapsed]/container:!hidden"
                    alt="{{ config('app.name') }}"
                />
            @endif

            <span class="hidden h-8 w-8 items-center justify-center rounded-md bg-admin-primary text-xs font-bold text-white group-[.sidebar-collapsed]/container:!flex">
                {{ strtoupper(substr(config('app.name'), 0, 1)) }}
            </span>
        </a>
    </div>

    <!-- ค้นหาเดสก์ท็อป -->
    <div class="hidden min-w-0 flex-1 items-center md:flex lg:col-start-2 lg:px-4">
        <v-mega-search ref="megaSearch" class="w-full max-w-none"></v-mega-search>
    </div>

    <!-- utilities + profile -->
    <div class="ml-auto flex shrink-0 items-center gap-2 sm:gap-3 lg:col-start-3 lg:ml-0 lg:px-4">
        <button
            type="button"
            class="admin-header-action shrink-0 md:hidden"
            aria-label="@lang('admin::app.components.layouts.header.mega-search.title')"
            @click="$refs.megaSearch.openMobileSearch()"
        >
            <i class="icon-search text-lg"></i>
        </button>

        <a
            href="{{ route('shop.home.index') }}"
            target="_blank"
            class="admin-header-action hidden md:inline-flex"
            title="@lang('admin::app.components.layouts.header.visit-shop')"
        >
            <i class="icon-store text-lg"></i>
        </a>

        <v-notifications {{ $attributes }}>
            <button type="button" class="admin-header-action relative" title="@lang('admin::app.components.layouts.header.notifications')">
                <i class="icon-notification text-lg"></i>
            </button>
        </v-notifications>

        <div class="hidden h-9 w-px bg-admin-border md:block"></div>

        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <x-slot:toggle>
                <button
                    type="button"
                    class="admin-header-profile flex items-center gap-2.5 rounded-lg py-1 transition-colors hover:bg-admin-surface ltr:pl-1 ltr:pr-2 rtl:pl-2 rtl:pr-1"
                >
                    @if ($admin->image)
                        <img
                            src="{{ $admin->image_url }}"
                            class="h-9 w-9 shrink-0 rounded-full object-cover ring-2 ring-admin-border"
                        />
                    @else
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-admin-primary text-sm font-semibold text-white">
                            {{ substr($admin->name, 0, 1) }}
                        </span>
                    @endif

                    <span class="hidden min-w-0 text-left md:block">
                        <span class="block truncate text-sm font-semibold leading-tight text-admin-text">
                            {{ $admin->name }}
                        </span>
                        <span class="block truncate text-xs leading-tight text-admin-muted">
                            {{ $admin->email }}
                        </span>
                    </span>

                    <i class="icon-sort-down hidden shrink-0 text-base text-admin-muted md:block"></i>
                </button>
            </x-slot>

            <!-- Admin Dropdown -->
            <x-slot:content class="!p-0">
                <div class="flex items-center gap-1.5 border border-b-gray-300 px-4 py-2 border-admin-border sm:px-5 sm:py-2.5">
                    <img
                        src="{{ bagisto_asset('images/logo.svg') }}"
                        class="sm:h-6 sm:w-6"
                        width="20"
                        height="20"
                    />

                    <!-- Version -->
                    <p class="text-xs text-gray-400 sm:text-sm">
                        @lang('admin::app.components.layouts.header.app-version', ['version' => 'v' . core()->version()])
                    </p>
                </div>

                <div class="grid gap-1 pb-2.5">
                    <a
                        class="cursor-pointer px-4 py-2 text-sm text-admin-text hover:bg-admin-surface text-admin-text sm:px-5 sm:text-base"
                        href="{{ route('admin.account.edit') }}"
                    >
                        @lang('admin::app.components.layouts.header.my-account')
                    </a>

                    <!--Admin logout-->
                    <x-admin::form
                        method="DELETE"
                        action="{{ route('admin.session.destroy') }}"
                        id="adminLogout"
                    >
                    </x-admin::form>

                    <a
                        class="cursor-pointer px-4 py-2 text-sm text-admin-text hover:bg-admin-surface text-admin-text sm:px-5 sm:text-base"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        @lang('admin::app.components.layouts.header.logout')
                    </a>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </div>
</header>

<!-- Menu Sidebar Drawer -->
<x-admin::drawer
    position="left"
    width="280px"
    :mobile-full-width="false"
    :lock-body-scroll="false"
    ref="sidebarMenuDrawer"
    class="beyondary-admin-mobile-sidebar-drawer"
>
    <!-- Drawer Header -->
    <x-slot:header>
        <div class="flex items-center justify-between pr-10">
            @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                <img
                    src="{{ Storage::url($logo) }}"
                    class="h-8 w-auto sm:h-10"
                    alt="{{ config('app.name') }}"
                />
            @else
                <img
                    src="{{ bagisto_asset('images/logo.svg') }}"
                    class="h-8 w-auto sm:h-10"
                    id="logo-image"
                    alt="{{ config('app.name') }}"
                />
            @endif
        </div>
    </x-slot>

    <!-- Drawer Content -->
    <x-slot:content class="!p-0">
        <div class="h-[calc(100vh-4.5rem)] bg-admin-sidebar">
            <v-beyondary-mobile-drawer-nav
                :items='@json($sidebarMenuItems)'
                help-url="{{ route('admin.help.index') }}"
                help-label="{{ __('admin::app.components.layouts.sidebar.help') }}"
                :help-active="{{ $isHelpActive ? 'true' : 'false' }}"
            ></v-beyondary-mobile-drawer-nav>
        </div>
    </x-slot>
</x-admin::drawer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-beyondary-mobile-drawer-nav-template"
    >
        <div class="beyondary-admin-drawer-nav flex h-full min-h-0 flex-col">
            <div class="sidebar-scroll flex-1 overflow-y-auto overflow-x-hidden px-2 py-2">
                <nav class="flex flex-col gap-0">
                    <div
                        v-for="item in items"
                        :key="item.key"
                        class="sidebar-menu-item"
                    >
                        <a
                            v-if="! item.children.length"
                            :href="item.url"
                            class="sidebar-nav-link flex w-full items-center gap-2.5 rounded-md px-2 py-2 transition-colors duration-200"
                            :class="{ 'sidebar-nav-link--active': item.active }"
                            @click="handleNavigate"
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
                                        @click="handleNavigate"
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
                    </div>
                </nav>
            </div>

            <div class="shrink-0 px-2 pb-3">
                <div class="mb-2 border-t border-white/10"></div>

                <a
                    :href="helpUrl"
                    class="sidebar-nav-link flex w-full items-center gap-2.5 rounded-md px-2 py-2 transition-colors duration-200"
                    :class="{ 'sidebar-nav-link--active': helpActive }"
                    @click="handleNavigate"
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
        app.component('v-beyondary-mobile-drawer-nav', {
            template: '#v-beyondary-mobile-drawer-nav-template',

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
                };
            },

            mounted() {
                this.expandedKeys = this.items
                    .filter((item) => item.active && item.children.length)
                    .map((item) => item.key);
            },

            methods: {
                isExpanded(key) {
                    return this.expandedKeys.includes(key);
                },

                toggle(key) {
                    if (this.expandedKeys.includes(key)) {
                        this.expandedKeys = [];
                    } else {
                        this.expandedKeys = [key];
                    }
                },

                handleNavigate() {
                    this.closeDrawer();
                },

                closeDrawer() {
                    let parent = this.$parent;

                    while (parent) {
                        if (typeof parent.close === 'function' && parent.isOpen !== undefined) {
                            parent.close();

                            return;
                        }

                        parent = parent.$parent;
                    }
                },
            },
        });
    </script>

    <script
        type="text/x-template"
        id="v-mega-search-panel-template"
    >
        <div class="flex min-h-0 flex-col" :class="variant === 'sheet' ? 'h-full' : ''">
            <div class="flex shrink-0 border-b border-admin-border text-xs text-admin-muted sm:text-sm">
                <div
                    class="cursor-pointer p-2 hover:bg-admin-surface sm:p-4"
                    :class="{ 'border-b-2 border-admin-primary': activeTab == tab.key }"
                    v-for="tab in tabs"
                    @click="$emit('select-tab', tab.key)"
                >
                    @{{ tab.title }}
                </div>
            </div>

                <!-- Searched Results -->
                <template v-if="activeTab == 'products'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.products />
                    </template>

                    <template v-else>
                        <div
                            class="grid overflow-y-auto"
                            :class="variant === 'sheet' ? 'min-h-0 flex-1' : 'max-h-[300px] sm:max-h-[400px]'"
                        >
                            <a
                                :href="'{{ route('admin.catalog.products.edit', ':id') }}'.replace(':id', product.id)"
                                class="flex cursor-pointer justify-between gap-2 border-b border-slate-300 p-3 last:border-b-0 hover:bg-gray-100 border-admin-border sm:gap-2.5 sm:p-4"
                                v-for="product in searchedResults.products.data"
                            >
                                <!-- Left Information -->
                                <div class="flex gap-2 sm:gap-2.5">
                                    <!-- Image -->
                                    <div
                                        class="relative h-10 max-h-10 w-full max-w-10 overflow-hidden rounded sm:h-[60px] sm:max-h-[60px] sm:max-w-[60px]"
                                        :class="{'overflow-hidden rounded border border-dashed border-gray-300 border-admin-border opacity-90': ! product.images.length}"
                                    >
                                        <template v-if="! product.images.length">
                                            <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}" class="h-full w-full object-cover">
                                        
                                            <p class="absolute bottom-0.5 w-full text-center text-[4px] font-semibold text-gray-400 sm:bottom-1.5 sm:text-[6px]">
                                                @lang('admin::app.catalog.products.edit.types.grouped.image-placeholder')
                                            </p>
                                        </template>

                                        <template v-else>
                                            <img :src="product.images[0].url" class="h-full w-full object-cover">
                                        </template>
                                    </div>

                                    <!-- Details -->
                                    <div class="grid place-content-start gap-1 sm:gap-1.5">
                                        <p class="text-sm font-semibold text-admin-muted sm:text-base">
                                            @{{ product.name }}
                                        </p>

                                        <p class="text-xs text-gray-500 sm:text-sm">
                                            @{{ "@lang('admin::app.components.layouts.header.mega-search.sku')".replace(':sku', product.sku) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Information -->
                                <div class="grid place-content-center gap-1 text-right">
                                    <p class="text-sm font-semibold text-admin-muted sm:text-base">
                                        @{{ product.formatted_price }}
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="flex border-t p-2 border-admin-border sm:p-3">
                            <a
                                :href="'{{ route('admin.catalog.products.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                                v-if="searchedResults.products.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-products')".replace(':query', searchTerm).replace(':count', searchedResults.products.meta.total) }}
                            </a>

                            <a
                                href="{{ route('admin.catalog.products.index') }}"
                                class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-products')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'orders'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.orders />
                    </template>

                    <template v-else>
                        <div
                            class="grid overflow-y-auto"
                            :class="variant === 'sheet' ? 'min-h-0 flex-1' : 'max-h-[300px] sm:max-h-[400px]'"
                        >
                            <a
                                :href="'{{ route('admin.sales.orders.view', ':id') }}'.replace(':id', order.id)"
                                class="grid cursor-pointer place-content-start gap-1 border-b border-slate-300 p-3 last:border-b-0 hover:bg-gray-100 border-admin-border sm:gap-1.5 sm:p-4"
                                v-for="order in searchedResults.orders.data"
                            >
                                <p class="text-sm font-semibold text-admin-muted sm:text-base">
                                    #@{{ order.increment_id }}
                                </p>

                                <p class="text-xs text-gray-500 text-admin-muted sm:text-sm">
                                    @{{ order.formatted_created_at + ', ' + order.status_label + ', ' + order.customer_full_name }}
                                </p>
                            </a>
                        </div>

                        <div class="flex border-t p-2 border-admin-border sm:p-3">
                            <a
                                :href="'{{ route('admin.sales.orders.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                                v-if="searchedResults.orders.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-orders')".replace(':query', searchTerm).replace(':count', searchedResults.orders.total) }}
                            </a>

                            <a
                                href="{{ route('admin.sales.orders.index') }}"
                                class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-orders')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'categories'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.categories />
                    </template>

                    <template v-else>
                        <div
                            class="grid overflow-y-auto"
                            :class="variant === 'sheet' ? 'min-h-0 flex-1' : 'max-h-[300px] sm:max-h-[400px]'"
                        >
                            <a
                                :href="'{{ route('admin.catalog.categories.edit', ':id') }}'.replace(':id', category.id)"
                                class="cursor-pointer border-b p-3 text-xs font-semibold text-gray-600 last:border-b-0 hover:bg-gray-100 border-admin-border text-admin-muted sm:p-4 sm:text-sm"
                                v-for="category in searchedResults.categories.data"
                            >
                                @{{ category.name }}
                            </a>
                        </div>

                        <div class="flex border-t p-2 border-admin-border sm:p-3">
                            <a
                                :href="'{{ route('admin.catalog.categories.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                                v-if="searchedResults.categories.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-categories')".replace(':query', searchTerm).replace(':count', searchedResults.categories.total) }}
                            </a>

                            <a
                                href="{{ route('admin.catalog.categories.index') }}"
                                class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-categories')
                            </a>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'customers'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.customers />
                    </template>

                    <template v-else>
                        <div
                            class="grid overflow-y-auto"
                            :class="variant === 'sheet' ? 'min-h-0 flex-1' : 'max-h-[300px] sm:max-h-[400px]'"
                        >
                            <a
                                :href="'{{ route('admin.customers.customers.view', ':id') }}'.replace(':id', customer.id)"
                                class="grid cursor-pointer place-content-start gap-1 border-b border-slate-300 p-3 last:border-b-0 hover:bg-gray-100 border-admin-border sm:gap-1.5 sm:p-4"
                                v-for="customer in searchedResults.customers.data"
                            >
                                <p class="text-sm font-semibold text-admin-muted sm:text-base">
                                    @{{ customer.first_name + ' ' + customer.last_name }}
                                </p>

                                <p class="text-xs text-gray-500 sm:text-sm">
                                    @{{ customer.email }}
                                </p>
                            </a>
                        </div>

                        <div class="flex border-t p-2 border-admin-border sm:p-3">
                            <a
                                :href="'{{ route('admin.customers.customers.index') }}?search=:query'.replace(':query', searchTerm)"
                                class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                                v-if="searchedResults.customers.data.length"
                            >
                                @{{ "@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-customers')".replace(':query', searchTerm).replace(':count', searchedResults.customers.total) }}
                            </a>

                            <a
                                href="{{ route('admin.customers.customers.index') }}"
                                class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                                v-else
                            >
                                @lang('admin::app.components.layouts.header.mega-search.explore-all-customers')
                            </a>
                        </div>
                    </template>
                </template>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-mega-search-template"
    >
        <div class="relative w-full">
            <div class="relative hidden w-full md:block">
                <i class="icon-search pointer-events-none absolute top-1/2 -translate-y-1/2 text-base text-admin-muted ltr:left-3 rtl:right-3"></i>

                <input
                    type="text"
                    class="admin-header-search-input block w-full rounded-lg border border-admin-border bg-admin-surface text-sm text-admin-text transition-all placeholder:text-admin-muted hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30 ltr:pl-9 ltr:pr-3 rtl:pl-3 rtl:pr-9"
                    placeholder="@lang('admin::app.components.layouts.header.mega-search.title')"
                    v-model.lazy="searchTerm"
                    @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                    v-debounce="500"
                >

                <div
                    class="absolute top-8 z-10 w-full rounded-lg border border-admin-border bg-admin-card shadow-lg sm:top-10"
                    v-if="isDropdownOpen && ! isMobileModalOpen"
                >
                    <v-mega-search-panel
                        :active-tab="activeTab"
                        :tabs="tabs"
                        :searched-results="searchedResults"
                        :is-loading="isLoading"
                        :search-term="searchTerm"
                        variant="dropdown"
                        @select-tab="onTabSelected"
                    />
                </div>
            </div>

            <Teleport to="body">
                <div
                    v-if="isMobileModalOpen"
                    class="fixed inset-0 z-[10002] flex flex-col md:hidden"
                >
                <div
                    class="absolute inset-0 bg-black/40"
                    @click="closeMobileSearch"
                ></div>

                <div class="relative z-10 flex min-h-0 flex-1 flex-col bg-admin-card">
                    <div class="flex shrink-0 items-center gap-2 border-b border-admin-border px-3 py-2.5">
                        <div class="relative min-w-0 flex-1">
                            <i class="icon-search pointer-events-none absolute top-1/2 -translate-y-1/2 text-base text-admin-muted ltr:left-3 rtl:right-3"></i>

                            <input
                                ref="mobileSearchInput"
                                type="text"
                                class="admin-header-search-input block w-full rounded-lg border border-admin-border bg-admin-surface text-sm text-admin-text transition-all placeholder:text-admin-muted hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30 ltr:pl-9 ltr:pr-3 rtl:pl-3 rtl:pr-9"
                                placeholder="@lang('admin::app.components.layouts.header.mega-search.title')"
                                v-model.lazy="searchTerm"
                                v-debounce="500"
                            >
                        </div>

                        <button
                            type="button"
                            class="admin-header-action shrink-0"
                            :aria-label="closeSearchLabel"
                            @click="closeMobileSearch"
                        >
                            <i class="icon-cross text-lg"></i>
                        </button>
                    </div>

                    <v-mega-search-panel
                        v-if="isDropdownOpen"
                        class="flex min-h-0 flex-1 flex-col overflow-hidden"
                        :active-tab="activeTab"
                        :tabs="tabs"
                        :searched-results="searchedResults"
                        :is-loading="isLoading"
                        :search-term="searchTerm"
                        variant="sheet"
                        @select-tab="onTabSelected"
                    />

                    <p
                        v-else
                        class="px-4 py-10 text-center text-sm text-admin-muted"
                    >
                        @lang('admin::app.components.layouts.header.mega-search.title')
                    </p>
                </div>
            </div>
            </Teleport>
        </div>
    </script>

    <script type="module">
        app.component('v-mega-search-panel', {
            template: '#v-mega-search-panel-template',

            props: {
                activeTab: {
                    type: String,
                    required: true,
                },

                tabs: {
                    type: Object,
                    required: true,
                },

                searchedResults: {
                    type: Object,
                    required: true,
                },

                isLoading: {
                    type: Boolean,
                    default: false,
                },

                searchTerm: {
                    type: String,
                    default: '',
                },

                variant: {
                    type: String,
                    default: 'dropdown',
                },
            },

            emits: ['select-tab'],
        });

        app.component('v-mega-search', {
            template: '#v-mega-search-template',

            data() {
                return {
                    activeTab: 'products',

                    isDropdownOpen: false,

                    isMobileModalOpen: false,

                    megaSearchLabel: "@lang('admin::app.components.layouts.header.mega-search.title')",

                    closeSearchLabel: "Close",

                    tabs: {
                        products: {
                            key: 'products',
                            title: "@lang('admin::app.components.layouts.header.mega-search.products')",
                            is_active: true,
                            endpoint: "{{ route('admin.catalog.products.search') }}"
                        },
                        
                        orders: {
                            key: 'orders',
                            title: "@lang('admin::app.components.layouts.header.mega-search.orders')",
                            endpoint: "{{ route('admin.sales.orders.search') }}"
                        },
                        
                        categories: {
                            key: 'categories',
                            title: "@lang('admin::app.components.layouts.header.mega-search.categories')",
                            endpoint: "{{ route('admin.catalog.categories.search') }}"
                        },
                        
                        customers: {
                            key: 'customers',
                            title: "@lang('admin::app.components.layouts.header.mega-search.customers')",
                            endpoint: "{{ route('admin.customers.customers.search') }}"
                        }
                    },

                    isLoading: false,

                    searchTerm: '',

                    searchedResults: {
                        products: [],
                        orders: [],
                        categories: [],
                        customers: []
                    },
                }
            },

            watch: {
                searchTerm: function(newVal, oldVal) {
                    this.search()
                }
            },

            created() {
                window.addEventListener('click', this.handleFocusOut);
                window.addEventListener('keydown', this.handleEscape);
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleFocusOut);
                window.removeEventListener('keydown', this.handleEscape);
                document.body.style.overflow = '';
            },

            methods: {
                openMobileSearch() {
                    this.isMobileModalOpen = true;
                    document.body.style.overflow = 'hidden';

                    this.$nextTick(() => {
                        this.$refs.mobileSearchInput?.focus();
                    });
                },

                closeMobileSearch() {
                    this.isMobileModalOpen = false;
                    this.isDropdownOpen = false;
                    document.body.style.overflow = '';
                },

                onTabSelected(tabKey) {
                    this.activeTab = tabKey;
                    this.search();
                },

                handleEscape(event) {
                    if (event.key === 'Escape' && this.isMobileModalOpen) {
                        this.closeMobileSearch();
                    }
                },

                search() {
                    if (this.searchTerm.length <= 1) {
                        this.searchedResults[this.activeTab] = [];

                        this.isDropdownOpen = false;

                        return;
                    }

                    this.isDropdownOpen = true;

                    let self = this;

                    this.isLoading = true;
                    
                    this.$axios.get(this.tabs[this.activeTab].endpoint, {
                            params: {query: this.searchTerm}
                        })
                        .then(function(response) {
                            self.searchedResults[self.activeTab] = response.data;

                            self.isLoading = false;
                        })
                        .catch(function (error) {
                        })
                },

                handleFocusOut(e) {
                    if (this.isMobileModalOpen) {
                        return;
                    }

                    if (! this.$el.contains(e.target)) {
                        this.isDropdownOpen = false;
                    }
                },
            }
        });
    </script>

    <script
        type="text/x-template"
        id="v-notifications-template"
    >
        <x-admin::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <!-- Notification Toggle -->
            <x-slot:toggle>
                <span class="admin-header-action relative">
                    <i class="icon-notification text-lg"></i>

                    <span
                        class="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-admin-primary px-1 text-[10px] font-semibold leading-none text-white"
                        v-if="totalUnRead"
                    >
                        @{{ totalUnRead }}
                    </span>
                </span>
            </x-slot>

            <!-- Notification Content -->
            <x-slot:content class="min-w-[250px] max-w-[250px] !p-0">
                <!-- Header -->
                <div class="border-b p-3 text-base font-semibold text-gray-600 border-admin-border text-admin-muted">
                    @lang('admin::app.notifications.title', ['read' => 0])
                </div>

                <!-- Content -->
                <div class="grid">
                    <a
                        class="flex items-start gap-1.5 border-b p-3 last:border-b-0 hover:bg-admin-surface border-admin-border"
                        v-for="notification in notifications"
                        :href="'{{ route('admin.notification.viewed_notification', ':orderId') }}'.replace(':orderId', notification.order_id)"
                    >
                        <!-- Notification Icon -->
                        <span
                            v-if="notification.order.status in notificationStatusIcon"
                            class="h-fit"
                            :class="notificationStatusIcon[notification.order.status]"
                        >
                        </span>

                        <div class="grid">
                            <!-- Order Id & Status -->
                            <p class="text-admin-text">
                                #@{{ notification.order.id }}
                                @{{ orderTypeMessages[notification.order.status] }}
                            </p>

                            <!-- Created Date In humand Readable Format -->
                            <p class="text-xs text-admin-muted">
                                @{{ notification.order.datetime }}
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="flex h-[47px] justify-between gap-1.5 border-t px-6 py-4 border-admin-border">
                    <a
                        href="{{ route('admin.notification.index') }}"
                        class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                    >
                        @lang('admin::app.notifications.view-all')
                    </a>

                    <a
                        class="cursor-pointer text-xs font-semibold text-admin-primary transition-all hover:underline"
                        v-if="notifications?.length"
                        @click="readAll()"
                    >
                        @lang('admin::app.notifications.read-all')
                    </a>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </script>

    <script type="module">
        app.component('v-notifications', {
            template: '#v-notifications-template',

                props: [
                    'getReadAllUrl',
                    'readAllTitle',
                ],

                data() {
                    return {
                        notifications: [],

                        ordertype: {
                            pending: {
                                icon: 'icon-information',
                                message: "@lang('admin::app.notifications.order-status-messages.pending-payment')"
                            },

                            processing: {
                                icon: 'icon-processing',
                                message: "@lang('admin::app.notifications.order-status-messages.processing')",
                            },

                            canceled: {
                                icon: 'icon-cancel-1',
                                message: "@lang('admin::app.notifications.order-status-messages.canceled')"
                            },

                            completed: {
                                icon: 'icon-done',
                                message: "@lang('admin::app.notifications.order-status-messages.completed')"
                            },

                            closed: {
                                icon: 'icon-cancel-1',
                                message: "@lang('admin::app.notifications.order-status-messages.closed')"
                            },

                            pending_payment: {
                                icon: "icon-information",
                                message: "@lang('admin::app.notifications.order-status-messages.pending-payment')"
                            },
                        },

                        totalUnRead: 0,

                        orderTypeMessages: {
                            {{ \Webkul\Sales\Models\Order::STATUS_PENDING }}: "@lang('admin::app.notifications.order-status-messages.pending')",
                            {{ \Webkul\Sales\Models\Order::STATUS_CANCELED }}: "@lang('admin::app.notifications.order-status-messages.canceled')",
                            {{ \Webkul\Sales\Models\Order::STATUS_CLOSED }}: "@lang('admin::app.notifications.order-status-messages.closed')",
                            {{ \Webkul\Sales\Models\Order::STATUS_COMPLETED }}: "@lang('admin::app.notifications.order-status-messages.completed')",
                            {{ \Webkul\Sales\Models\Order::STATUS_PROCESSING }}: "@lang('admin::app.notifications.order-status-messages.processing')",
                            {{ \Webkul\Sales\Models\Order::STATUS_PENDING_PAYMENT }}: "@lang('admin::app.notifications.order-status-messages.pending-payment')",
                        }
                    }
                },

                computed: {
                    notificationStatusIcon() {
                        return {
                            pending: 'icon-information rounded-full bg-amber-100 text-2xl text-amber-600',
                            closed: 'icon-repeat rounded-full bg-red-100 text-2xl text-red-600',
                            completed: 'icon-done rounded-full bg-blue-100 text-2xl text-admin-primary',
                            canceled: 'icon-cancel-1 rounded-full bg-red-100 text-2xl text-red-600',
                            processing: 'icon-sort-right rounded-full bg-green-100 text-2xl text-green-600',
                        };
                    },
                },

                mounted() {
                    this.getNotification();
                },

                methods: {
                    getNotification() {
                        this.$axios.get('{{ route('admin.notification.get_notification') }}', {
                                params: {
                                    limit: 5,
                                    read: 0
                                }
                            })
                            .then((response) => {
                                this.notifications = response.data.search_results.data;

                                this.totalUnRead =   response.data.total_unread;
                            })
                            .catch(error => console.log(error))
                    },

                    readAll() {
                        this.$axios.post('{{ route('admin.notification.read_all') }}')
                            .then((response) => {
                                this.notifications = response.data.search_results.data;

                                this.totalUnRead = response.data.total_unread;

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.success_message });
                        })
                        .catch((error) => {});
                },
            },
        });
    </script>

@endpushOnce