@props([
    'type' => 'text',
    'name' => '',
])

@switch($type)
    @case('hidden')
    @case('text')
    @case('email')
    @case('password')
    @case('number')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
            />
        </v-field>

        @break

    @case('price')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <div
                class="flex w-full items-center overflow-hidden rounded-md border border-admin-border bg-admin-card text-sm text-admin-text transition-all hover:border-admin-muted focus-within:border-admin-border focus-within:outline-none focus-within:ring-2 focus-within:ring-admin-primary/30"
                :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
            >
                @if (isset($currency))
                    <span {{ $currency->attributes->merge(['class' => 'py-2.5 text-gray-500 ltr:pl-4 rtl:pr-4']) }}>
                        {{ $currency }}
                    </span>
                @else
                    <span class="py-2.5 text-gray-500 ltr:pl-4 rtl:pr-4">
                        {{ core()->currencySymbol(core()->getBaseCurrencyCode()) }}
                    </span>
                @endif

                <input
                    type="text"
                    name="{{ $name }}"
                    v-bind="field"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full bg-transparent p-2.5 text-sm text-admin-text']) }}
                />
            </div>
        </v-field>

        @break

    @case('file')
        <v-field
            v-slot="{ field, errors, handleChange, handleBlur }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <input
                type="{{ $type }}"
                v-bind="{ name: field.name }"
                :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm text-admin-text transition-all file:bg-admin-surface file:text-admin-text hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
                @change="handleChange"
                @blur="handleBlur"
            />
        </v-field>

        @break

    @case('color')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->except('class') }}
        >
            <input
                type="{{ $type }}"
                :class="[errors.length ? 'border border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'w-full appearance-none rounded-md border border-admin-border bg-admin-card text-sm text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
            >
        </v-field>
        @break

    @case('textarea')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <textarea
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
            >
            </textarea>

            @if ($attributes->get('tinymce', false) || $attributes->get(':tinymce', false))
                <x-admin::tinymce
                    :selector="'textarea#' . $attributes->get('id')"
                    :prompt="stripcslashes($attributes->get('prompt', ''))"
                    ::field="field"
                >
                </x-admin::tinymce>
            @endif
        </v-field>

        @break

    @case('date')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <x-admin::flat-picker.date {{ $attributes }}>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
                    autocomplete="off"
                />
            </x-admin::flat-picker.date>
        </v-field>

        @break

    @case('datetime')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <x-admin::flat-picker.datetime>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
                    autocomplete="off"
                >
            </x-admin::flat-picker.datetime>
        </v-field>
        @break

    @case('time')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-admin::flat-picker.time>
                <input
                    type="time"
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'flex w-full min-h-[39px] rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
                    autocomplete="off"
                >
            </x-admin::flat-picker.time>
        </v-field>
        @break

    @case('select')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <select
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'custom-select w-full rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm font-normal text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
            >
                {{ $slot }}
            </select>
        </v-field>

        @break

    @case('multiselect')
        <v-field
            as="select"
            v-slot="{ value }"
            :class="[errors && errors['{{ $name }}'] ? 'border !border-red-600 hover:border-red-600' : '']"
            {{ $attributes->except([])->merge(['class' => 'flex w-full flex-col rounded-md border border-admin-border bg-admin-card px-3 py-2.5 text-sm font-normal text-admin-text transition-all hover:border-admin-muted focus:border-admin-border focus:outline-none focus:ring-2 focus:ring-admin-primary/30']) }}
            name="{{ $name }}"
            multiple
        >
            {{ $slot }}
        </v-field>

        @break

    @case('checkbox')
        <v-field
            v-slot="{ field }"
            type="checkbox"
            class="hidden"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
            name="{{ $name }}"
        >
            <input
                type="checkbox"
                name="{{ $name }}"
                v-bind="field"
                class="peer sr-only"
                {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
            />

            <v-checked-handler
                :field="field"
                checked="{{ $attributes->get('checked') }}"
            >
            </v-checked-handler>
        </v-field>

        <label
             {{
                $attributes
                    ->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key'])
                    ->merge(['class' => 'icon-uncheckbox peer-checked:icon-checked text-2xl peer-checked:text-admin-primary'])
                    ->merge(['class' => $attributes->get('disabled') ? 'cursor-not-allowed opacity-70' : 'cursor-pointer'])
            }}
        >
        </label>

        @break

    @case('radio')
        <v-field
            type="radio"
            class="hidden"
            v-slot="{ field }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
            name="{{ $name }}"
        >
            <input
                type="radio"
                name="{{ $name }}"
                v-bind="field"
                class="peer sr-only"
                {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
            />

            <v-checked-handler
                class="hidden"
                :field="field"
                checked="{{ $attributes->get('checked') }}"
            >
            </v-checked-handler>
        </v-field>

        <label
            class="icon-radio-normal peer-checked:icon-radio-selected cursor-pointer text-2xl peer-checked:text-admin-primary"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
        >
        </label>

        @break

    @case('switch')
        <label class="relative inline-flex cursor-pointer items-center">
            <v-field
                type="checkbox"
                class="hidden"
                v-slot="{ field }"
                {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
                name="{{ $name }}"
            >
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="peer sr-only"
                    v-bind="field"
                    {{ $attributes->except(['v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
                />

                <v-checked-handler
                    class="hidden"
                    :field="field"
                    checked="{{ $attributes->get('checked') }}"
                >
                </v-checked-handler>
            </v-field>

            <label
                class="peer h-5 w-9 cursor-pointer rounded-full bg-admin-border after:absolute after:top-0.5 after:h-4 after:w-4 after:rounded-full after:border after:border-admin-border after:bg-white after:transition-all after:content-[''] peer-checked:bg-admin-primary peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-admin-primary/30 after:ltr:left-0.5 peer-checked:after:ltr:translate-x-full after:rtl:right-0.5 peer-checked:after:rtl:-translate-x-full"
                for="{{ $name }}"
            ></label>
        </label>

        @break

    @case('image')
        <x-admin::media.images
            name="{{ $name }}"
            ::class="[errors && errors['{{ $name }}'] ? 'border !border-red-600 hover:border-red-600' : '']"
            {{ $attributes }}
        />

        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checked-handler-template"
    >
    </script>

    <script type="module">
        app.component('v-checked-handler', {
            template: '#v-checked-handler-template',

            props: ['field', 'checked'],

            mounted() {
                if (this.checked == '') {
                    return;
                }

                this.field.checked = true;

                this.field.onChange();
            },
        });
    </script>
@endpushOnce
