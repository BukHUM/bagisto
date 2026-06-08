@props(['options'])

@php
    $slides = collect($options['images'] ?? [])->filter(fn ($slide) => ! empty($slide['image']));
    $firstImage = $slides->first();
@endphp

@if ($slides->isEmpty())
    @include('shop::home.partials.hero')
@elseif ($slides->count() === 1)
    @php
        $slide = $slides->first();
        $imageUrl = asset($slide['image']);
    @endphp

    <section class="relative w-full h-[60vh] md:h-[80vh] flex items-center justify-center bg-brand-dark">
        <div class="absolute inset-0 bg-brand-dark">
            @if (! empty($slide['link']))
                <a href="{{ $slide['link'] }}" class="block w-full h-full">
            @endif
            <img
                src="{{ $imageUrl }}"
                alt="{{ $slide['title'] ?? '' }}"
                class="w-full h-full object-cover opacity-50 mix-blend-overlay"
            >
            @if (! empty($slide['link']))
                </a>
            @endif
        </div>

        <div class="relative z-10 text-center px-4 max-w-3xl mx-auto mt-8 md:mt-0 pointer-events-none">
            <span class="text-brand-gold font-sans tracking-widest uppercase text-sm md:text-base font-semibold mb-4 block">
                @lang('beyondary.hero.tagline')
            </span>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl text-white font-bold mb-6 leading-tight">
                {{ $slide['title'] ?: __('beyondary.hero.title') }}
            </h1>
            <p class="text-brand-light/90 text-base md:text-lg mb-8 font-light max-w-xl mx-auto">
                @lang('beyondary.hero.subtitle')
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 pointer-events-auto">
                <a
                    href="{{ $slide['link'] ?: route('shop.search.index') }}"
                    class="bg-brand-gold hover:bg-[#A07644] text-white px-8 py-3 rounded-none font-medium tracking-wide transition duration-300"
                >
                    @lang('beyondary.hero.cta_shop')
                </a>
                <a
                    href="{{ route('shop.home.index') }}#artisans"
                    class="bg-transparent border border-brand-light text-brand-light hover:bg-brand-light hover:text-brand-dark px-8 py-3 rounded-none font-medium tracking-wide transition duration-300"
                >
                    @lang('beyondary.hero.cta_story')
                </a>
            </div>
        </div>
    </section>
@else
    @php
        $carouselImages = $slides->map(function ($slide) {
            return [
                'image' => asset($slide['image']),
                'link' => $slide['link'] ?? '',
                'title' => $slide['title'] ?? '',
            ];
        })->values()->all();
    @endphp

    <v-beyondary-hero-carousel :images="{{ json_encode($carouselImages) }}">
        <section class="relative w-full h-[60vh] md:h-[80vh] bg-brand-dark">
            <img
                src="{{ $carouselImages[0]['image'] }}"
                alt="{{ $carouselImages[0]['title'] }}"
                class="w-full h-full object-cover opacity-50 mix-blend-overlay"
            >
        </section>
    </v-beyondary-hero-carousel>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-beyondary-hero-carousel-template">
            <section class="relative w-full h-[60vh] md:h-[80vh] overflow-hidden bg-brand-dark">
                <div
                    class="inline-flex h-full transition-transform duration-700 ease-out will-change-transform"
                    ref="sliderContainer"
                >
                    <div
                        class="relative w-screen h-[60vh] md:h-[80vh] flex-shrink-0 flex items-center justify-center"
                        v-for="(image, index) in images"
                        ref="slide"
                    >
                        <div class="absolute inset-0 bg-brand-dark">
                            <img
                                :src="image.image"
                                :alt="image.title"
                                class="w-full h-full object-cover opacity-50 mix-blend-overlay"
                            >
                        </div>
                        <div
                            class="relative z-10 text-center px-4 max-w-3xl mx-auto"
                            v-if="image.title"
                        >
                            <h2 class="font-serif text-3xl md:text-5xl text-white font-bold leading-tight" v-text="image.title"></h2>
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-brand-dark/60 text-white hover:bg-brand-gold transition hidden md:flex items-center justify-center"
                    v-if="images.length >= 2"
                    @click="navigate('prev')"
                    aria-label="@lang('shop::components.carousel.previous')"
                >
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                </button>

                <button
                    type="button"
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-brand-dark/60 text-white hover:bg-brand-gold transition hidden md:flex items-center justify-center"
                    v-if="images.length >= 2"
                    @click="navigate('next')"
                    aria-label="@lang('shop::components.carousel.next')"
                >
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                </button>

                <div
                    class="absolute bottom-6 left-0 flex w-full justify-center gap-2 z-20"
                    v-if="images.length >= 2"
                >
                    <button
                        type="button"
                        v-for="(image, index) in images"
                        class="h-2 rounded-full transition-all"
                        :class="index === Math.abs(currentIndex) ? 'w-8 bg-brand-gold' : 'w-2 bg-white/40'"
                        @click="goTo(index)"
                        :aria-label="'Slide ' + (index + 1)"
                    ></button>
                </div>
            </section>
        </script>

        <script type="module">
            app.component('v-beyondary-hero-carousel', {
                template: '#v-beyondary-hero-carousel-template',

                props: ['images'],

                data() {
                    return {
                        currentIndex: 0,
                        autoPlayInterval: null,
                    };
                },

                mounted() {
                    this.$nextTick(() => {
                        this.setPosition();
                        this.play();
                    });
                },

                beforeUnmount() {
                    clearInterval(this.autoPlayInterval);
                },

                methods: {
                    setPosition() {
                        const container = this.$refs.sliderContainer;

                        if (! container) {
                            return;
                        }

                        container.style.transform = `translateX(${this.currentIndex * -window.innerWidth}px)`;
                    },

                    navigate(type) {
                        clearInterval(this.autoPlayInterval);

                        if (type === 'next') {
                            this.currentIndex = (this.currentIndex + 1) % this.images.length;
                        } else {
                            this.currentIndex = this.currentIndex > 0
                                ? this.currentIndex - 1
                                : this.images.length - 1;
                        }

                        this.setPosition();
                        this.play();
                    },

                    goTo(index) {
                        clearInterval(this.autoPlayInterval);
                        this.currentIndex = index;
                        this.setPosition();
                        this.play();
                    },

                    play() {
                        clearInterval(this.autoPlayInterval);

                        if (this.images.length < 2) {
                            return;
                        }

                        this.autoPlayInterval = setInterval(() => {
                            this.currentIndex = (this.currentIndex + 1) % this.images.length;
                            this.setPosition();
                        }, 6000);
                    },
                },
            });
        </script>
    @endPushOnce
@endif
