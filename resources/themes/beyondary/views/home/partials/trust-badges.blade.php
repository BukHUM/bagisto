@php
    $services = $services ?? null;
    $defaultBadges = [
        ['icon' => 'earth-americas', 'title' => __('beyondary.trust.shipping_title'), 'description' => __('beyondary.trust.shipping_desc')],
        ['icon' => 'hands-holding-circle', 'title' => __('beyondary.trust.authentic_title'), 'description' => __('beyondary.trust.authentic_desc')],
        ['icon' => 'shield-halved', 'title' => __('beyondary.trust.payment_title'), 'description' => __('beyondary.trust.payment_desc')],
    ];
    $iconMap = [
        'icon-truck' => 'fa-truck',
        'icon-product' => 'fa-box-open',
        'icon-dollar-sign' => 'fa-credit-card',
        'icon-support' => 'fa-headset',
    ];
@endphp

<section class="bg-white py-10 border-b border-[#EAE5DA]">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-[#EAE5DA]">
            @if (! empty($services))
                @foreach ($services as $service)
                    @php
                        $faIcon = $iconMap[$service['service_icon'] ?? ''] ?? 'fa-circle-check';
                    @endphp
                    <div class="flex flex-col items-center p-4">
                        <i class="fa-solid {{ $faIcon }} text-3xl text-brand-gold mb-3" aria-hidden="true"></i>
                        <h3 class="text-lg font-semibold text-brand-dark mb-1">{{ $service['title'] }}</h3>
                        <p class="text-brand-dark/60 text-sm">{{ $service['description'] }}</p>
                    </div>
                @endforeach
            @else
                @foreach ($defaultBadges as $badge)
                    <div class="flex flex-col items-center p-4">
                        <i class="fa-solid fa-{{ $badge['icon'] }} text-3xl text-brand-gold mb-3" aria-hidden="true"></i>
                        <h3 class="text-lg font-semibold text-brand-dark mb-1">{{ $badge['title'] }}</h3>
                        <p class="text-brand-dark/60 text-sm">{{ $badge['description'] }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
