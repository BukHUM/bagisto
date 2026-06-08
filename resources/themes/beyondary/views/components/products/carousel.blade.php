<v-products-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @for ($i = 0; $i < 4; $i++)
            <div class="product-card">
                <div class="relative overflow-hidden-image aspect-[4/5] mb-4 bg-brand-light rounded-sm shimmer"></div>
                <div class="text-center space-y-2">
                    <div class="shimmer h-3 w-20 mx-auto rounded"></div>
                    <div class="shimmer h-5 w-40 mx-auto rounded"></div>
                    <div class="shimmer h-6 w-24 mx-auto rounded"></div>
                </div>
            </div>
        @endfor
    </div>
</v-products-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-products-carousel-template">
        <div v-if="! isLoading && products.length">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <x-shop::products.card
                    class="product-card"
                    v-for="product in products"
                />
            </div>
        </div>

        <template v-if="isLoading">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @for ($i = 0; $i < 4; $i++)
                    <div class="product-card">
                        <div class="relative overflow-hidden-image aspect-[4/5] mb-4 bg-brand-light rounded-sm shimmer"></div>
                        <div class="text-center space-y-2">
                            <div class="shimmer h-3 w-20 mx-auto rounded"></div>
                            <div class="shimmer h-5 w-40 mx-auto rounded"></div>
                            <div class="shimmer h-6 w-24 mx-auto rounded"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-products-carousel', {
            template: '#v-products-carousel-template',

            props: ['src', 'title', 'navigationLink'],

            data() {
                return {
                    isLoading: true,
                    products: [],
                };
            },

            mounted() {
                this.getProducts();
            },

            methods: {
                getProducts() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.isLoading = false;
                            this.products = response.data.data;
                        })
                        .catch(() => {
                            this.isLoading = false;
                        });
                },
            },
        });
    </script>
@endPushOnce
