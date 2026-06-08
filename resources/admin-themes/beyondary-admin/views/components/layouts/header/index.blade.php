@php
    $admin = auth()->guard('admin')->user();
    $adminLogo = core()->getConfigData('general.design.admin_logo.logo_image');
@endphp

<header class="beyondary-admin-header sticky top-0 z-[10001] flex h-14 items-center gap-2 border-b border-admin-border bg-admin-card px-3 sm:gap-3 lg:grid lg:grid-cols-[270px_1fr_auto] lg:items-center lg:gap-0 lg:px-0 group-[.sidebar-collapsed]/container:lg:grid-cols-[70px_1fr_auto]">
    <!-- โซนโลโก้ — กว้างเท่า sidebar พื้นขาวให้โลโก้มองเห็นชัด -->
    <div class="admin-header-logo-zone flex w-auto shrink-0 items-center gap-2 px-0 lg:col-start-1 lg:h-14 lg:w-[270px] lg:shrink-0 lg:border-r lg:border-admin-border lg:bg-admin-card lg:px-4 group-[.sidebar-collapsed]/container:lg:w-[70px] group-[.sidebar-collapsed]/container:lg:justify-center group-[.sidebar-collapsed]/container:lg:px-2">
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

    <!-- ค้นหา — ข้อ 1: เริ่มแนวเดียวกับเนื้อหาหลัก -->
    <div class="flex min-w-0 flex-1 items-center lg:col-start-2 lg:px-4">
        <v-mega-search class="w-full max-w-none">
            <div class="relative w-full">
                <i class="icon-search pointer-events-none absolute top-1/2 -translate-y-1/2 text-base text-admin-muted ltr:left-3 rtl:right-3"></i>

                <input
                    type="text"
                    class="admin-header-search-input block w-full rounded-lg border border-admin-border bg-admin-surface text-sm text-admin-text transition-all placeholder:text-admin-muted hover:border-admin-primary/40 focus:border-admin-primary focus:outline-none focus:ring-2 focus:ring-admin-primary/15 ltr:pl-9 ltr:pr-3 rtl:pl-3 rtl:pr-9"
                    placeholder="@lang('admin::app.components.layouts.header.mega-search.title')"
                >
            </div>
        </v-mega-search>
    </div>

    <!-- utilities + profile -->
    <div class="ml-auto flex shrink-0 items-center gap-2 sm:gap-3 lg:col-start-3 lg:ml-0 lg:px-4">
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
    width="270px"
    ref="sidebarMenuDrawer"
>
    <!-- Drawer Header -->
    <x-slot:header>
        <div class="flex items-center justify-between">
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
    <x-slot:content class="p-3 sm:p-4">
        <div class="journal-scroll h-[calc(100vh-100px)] overflow-auto">
            <nav class="flex flex-col gap-0.5">
                @foreach (menu()->getItems('admin') as $menuItem)
                    @php
                        $isDrawerActive = $menuItem->isActive() == 'active';
                    @endphp

                    <div class="group/item relative">
                        <a
                            href="{{ $menuItem->getUrl() }}"
                            class="flex items-center gap-2.5 rounded-md px-2.5 py-2.5 text-sm transition-colors {{ $isDrawerActive ? 'bg-admin-primary font-semibold text-white' : 'font-medium text-admin-muted hover:bg-admin-surface hover:text-admin-text' }}"
                        >
                            <span class="{{ $menuItem->getIcon() }} text-lg {{ $isDrawerActive ? 'text-white' : 'text-admin-muted' }}"></span>

                            <p class="flex-1 whitespace-nowrap">
                                {{ $menuItem->getName() }}
                            </p>

                            @if ($menuItem->haveChildren())
                                <i class="icon-arrow-down text-sm opacity-70"></i>
                            @endif
                        </a>

                        @if ($menuItem->haveChildren())
                            <div class="{{ $menuItem->isActive() ? ' !grid bg-admin-surface' : '' }} hidden min-w-[180px] ltr:pl-8 rtl:pr-8 pb-2 rounded-b-lg z-[100] sm:ltr:pl-10 sm:rtl:pr-10">
                                @foreach ($menuItem->getChildren() as $subMenuItem)
                                    <a
                                        href="{{ $subMenuItem->getUrl() }}"
                                        class="text-xs whitespace-nowrap py-1 group-[.sidebar-collapsed]/container:px-4 group-[.sidebar-collapsed]/container:py-2 group-[.inactive]/item:px-4 group-[.inactive]/item:py-2 hover:text-admin-primary sm:text-sm sm:group-[.sidebar-collapsed]/container:px-5 sm:group-[.sidebar-collapsed]/container:py-2.5 sm:group-[.inactive]/item:px-5 sm:group-[.inactive]/item:py-2.5 {{ $subMenuItem->isActive() ? 'text-admin-primary' : 'text-admin-muted' }}"
                                    >
                                        {{ $subMenuItem->getName() }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>
        </div>
    </x-slot>
</x-admin::drawer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mega-search-template"
    >
        <div class="relative w-full">
            <i class="icon-search pointer-events-none absolute top-1/2 -translate-y-1/2 text-base text-admin-muted ltr:left-3 rtl:right-3"></i>

            <input
                type="text"
                class="admin-header-search-input peer block w-full rounded-lg border border-admin-border bg-admin-surface text-sm text-admin-text transition-all placeholder:text-admin-muted hover:border-admin-primary/40 focus:border-admin-primary focus:outline-none focus:ring-2 focus:ring-admin-primary/15 ltr:pl-9 ltr:pr-3 rtl:pl-3 rtl:pr-9"
                :class="{'border-admin-primary ring-2 ring-admin-primary/15': isDropdownOpen}"
                placeholder="@lang('admin::app.components.layouts.header.mega-search.title')"
                v-model.lazy="searchTerm"
                @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                v-debounce="500"
            >

            <div
                class="absolute top-8 z-10 w-full rounded-lg border bg-white shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] border-admin-border  sm:top-10"
                v-if="isDropdownOpen"
            >
                <!-- Search Tabs -->
                <div class="flex border-b text-xs text-gray-600 border-admin-border text-admin-muted sm:text-sm">
                    <div
                        class="cursor-pointer p-2 hover:bg-gray-100 sm:p-4"
                        :class="{ 'border-b-2 border-admin-primary': activeTab == tab.key }"
                        v-for="tab in tabs"
                        @click="activeTab = tab.key; search();"
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
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
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
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
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
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
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
                        <div class="grid max-h-[300px] overflow-y-auto sm:max-h-[400px]">
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
        </div>
    </script>

    <script type="module">
        app.component('v-mega-search', {
            template: '#v-mega-search-template',

            data() {
                return {
                    activeTab: 'products',

                    isDropdownOpen: false,

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
            },

            beforeDestroy() {
                window.removeEventListener('click', this.handleFocusOut);
            },

            methods: {
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