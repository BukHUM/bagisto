<?php

namespace Beyondary\Storefront\Http\Controllers\Admin;

use Beyondary\Storefront\Services\HomeSectionService;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class HomeCustomizationController extends Controller
{
    public function __construct(
        protected HomeSectionService $homeSectionService
    ) {}

    public function index(): View
    {
        $channel = $this->homeSectionService->channel();

        $customizations = $this->homeSectionService->exportableSections($channel)
            ->filter(fn ($item) => (bool) $item->status);

        $sections = collect(HomeSectionService::SECTIONS)->map(function (array $meta, string $key) use ($customizations) {
            $items = $customizations->filter(function ($item) use ($meta, $key) {
                if ($item->type !== $meta['type']) {
                    return false;
                }

                return match ($key) {
                    'our_story' => $item->name === HomeSectionService::OUR_STORY_NAME,
                    'menu' => $item->name === HomeSectionService::NAVIGATION_NAME,
                    'footer' => $item->type === $meta['type'],
                    default => true,
                };
            })->values();

            return [
                'key' => $key,
                'type' => $meta['type'],
                'label' => $meta['label'],
                'description' => 'beyondary-storefront::app.sections.'.$key,
                'singleton' => $meta['singleton'],
                'items' => $items,
                'configured' => $items->isNotEmpty(),
                'editUrl' => route('admin.beyondary.storefront.sections.edit', $key),
            ];
        });

        return view('beyondary-storefront::admin.home.index', [
            'channel' => $channel,
            'sections' => $sections,
            'storefrontUrl' => route('shop.home.index'),
            'exportZipUrl' => route('admin.beyondary.storefront.export'),
            'exportJsonUrl' => route('admin.beyondary.storefront.export', ['format' => 'json']),
        ]);
    }
}
