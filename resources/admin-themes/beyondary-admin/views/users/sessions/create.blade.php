<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.sessions.title')
    </x-slot>

    @push('meta')
        <meta name="robots" content="noindex, nofollow">
    @endpush

    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
        <div
            class="pointer-events-none absolute inset-0 bg-admin-surface"
            aria-hidden="true"
        >
            <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-admin-primary/5 blur-3xl"></div>
            <div class="absolute -bottom-28 -right-20 h-96 w-96 rounded-full bg-admin-primary/[0.07] blur-3xl"></div>
        </div>

        <div class="relative z-10 mx-auto flex w-full max-w-sm flex-col items-center gap-8">
            <div class="flex flex-col items-center gap-3 text-center">
                @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                    <img
                        class="h-12 w-auto max-w-[200px] object-contain"
                        src="{{ Storage::url($logo) }}"
                        alt="{{ config('app.name') }}"
                        width="200"
                        height="48"
                        decoding="async"
                    />
                @else
                    <img
                        class="h-12 w-auto max-w-[220px] object-contain"
                        src="{{ bagisto_asset('images/logo.svg') }}"
                        alt="{{ config('app.name') }}"
                        width="220"
                        height="48"
                        decoding="async"
                    />
                @endif

                <p class="font-sans text-[0.65rem] font-medium uppercase tracking-[0.35em] text-admin-muted">
                    Thai Craft
                </p>
            </div>

            <div class="w-full overflow-hidden rounded-xl border border-admin-border bg-admin-card shadow-lg">
                <x-admin::form :action="route('admin.session.store')">
                    <div class="border-b border-admin-border bg-admin-primary/[0.04] px-6 py-5">
                        <h1 class="font-display text-2xl font-semibold text-admin-text">
                            @lang('admin::app.users.sessions.title')
                        </h1>
                    </div>

                    <div class="flex flex-col gap-5 p-6">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required text-admin-text">
                                @lang('admin::app.users.sessions.email')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="email"
                                class="bg-white placeholder:text-admin-muted/60"
                                id="email"
                                name="email"
                                rules="required|email"
                                autocomplete="username"
                                maxlength="255"
                                :label="trans('admin::app.users.sessions.email')"
                                :placeholder="trans('admin::app.users.sessions.email')"
                            />

                            <x-admin::form.control-group.error control-name="email" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="relative w-full">
                            <x-admin::form.control-group.label class="required text-admin-text">
                                @lang('admin::app.users.sessions.password')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="password"
                                class="bg-white placeholder:text-admin-muted/60 ltr:pr-11 rtl:pl-11"
                                id="password"
                                name="password"
                                rules="required|min:6"
                                autocomplete="current-password"
                                maxlength="128"
                                :label="trans('admin::app.users.sessions.password')"
                                :placeholder="trans('admin::app.users.sessions.password')"
                            />

                            <button
                                type="button"
                                class="icon-view absolute top-[42px] -translate-y-1/2 cursor-pointer text-xl text-admin-muted transition-colors hover:text-admin-primary ltr:right-3 rtl:left-3"
                                id="visibilityToggle"
                                aria-label="{{ __('Toggle password visibility') }}"
                                aria-pressed="false"
                            >
                            </button>

                            <x-admin::form.control-group.error control-name="password" />
                        </x-admin::form.control-group>
                    </div>

                    <div class="flex items-center justify-between gap-4 border-t border-admin-border px-6 py-4">
                        <a
                            class="text-xs font-semibold leading-6 text-admin-primary transition-colors hover:text-admin-primary-hover"
                            href="{{ route('admin.forget_password.create') }}"
                        >
                            @lang('admin::app.users.sessions.forget-password-link')
                        </a>

                        <button
                            type="submit"
                            class="inline-flex shrink-0 items-center justify-center rounded-md border border-admin-primary-hover bg-admin-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-admin-primary-hover hover:shadow-md focus:outline-none focus:ring-2 focus:ring-admin-primary/30 focus:ring-offset-2"
                            aria-label="{{ trans('admin::app.users.sessions.submit-btn') }}"
                        >
                            @lang('admin::app.users.sessions.submit-btn')
                        </button>
                    </div>
                </x-admin::form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const passwordField = document.getElementById('password');
                const visibilityToggle = document.getElementById('visibilityToggle');

                if (! passwordField || ! visibilityToggle) {
                    return;
                }

                const toggleVisibility = () => {
                    const isHidden = passwordField.type === 'password';

                    passwordField.type = isHidden ? 'text' : 'password';
                    visibilityToggle.classList.toggle('icon-view-close', isHidden);
                    visibilityToggle.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                };

                visibilityToggle.addEventListener('click', toggleVisibility);

                visibilityToggle.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        toggleVisibility();
                    }
                });
            })();
        </script>
    @endpush
</x-admin::layouts.anonymous>
