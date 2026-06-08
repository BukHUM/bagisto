@if (! empty($data['css'] ?? null))
    @push('styles')
        <style>
            {{ $data['css'] }}
        </style>
    @endpush
@endif

@if (! empty($data['html']))
    <section class="beyondary-static-content">
        {!! $data['html'] !!}
    </section>
@endif
