<?php

namespace Beyondary\Storefront\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Contracts\ThemeCustomization;
use Webkul\Theme\Models\ThemeCustomization as ThemeCustomizationModel;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;
use ZipArchive;

class StorefrontTransferService
{
    public const MANIFEST_FILE = 'manifest.json';

    public const ASSETS_DIR = 'assets';

    public function __construct(
        protected ThemeCustomizationRepository $themeCustomizationRepository,
        protected HomeSectionService $homeSectionService
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function buildManifest(?Channel $channel = null, int $version = 2): array
    {
        $channel ??= $this->homeSectionService->channel();

        $sections = $this->homeSectionService->exportableSections($channel)->map(function (ThemeCustomization $theme) {
            $translations = [];

            foreach ($theme->translations as $translation) {
                $translations[$translation->locale] = [
                    'options' => $translation->options,
                ];
            }

            return [
                'type' => $theme->type,
                'name' => $theme->name,
                'sort_order' => $theme->sort_order,
                'status' => (bool) $theme->status,
                'translations' => $translations,
            ];
        })->values()->all();

        $assets = $this->collectAssetsFromSections($sections);

        return [
            'version' => $version,
            'theme_code' => $channel->theme,
            'channel_code' => $channel->code,
            'exported_at' => now()->toIso8601String(),
            'sections' => $sections,
            'assets' => array_keys($assets),
        ];
    }

    /**
     * @return array<string, string> disk path => absolute file path
     */
    public function collectAssetsFromSections(array $sections): array
    {
        $assets = [];

        foreach ($sections as $section) {
            foreach ($section['translations'] ?? [] as $translation) {
                foreach ($this->extractStoragePaths($translation['options'] ?? []) as $path) {
                    $absolute = $this->absolutePathForDisk($path);

                    if ($absolute) {
                        $assets[$path] = $absolute;
                    }
                }
            }
        }

        return $assets;
    }

    public function exportJson(?Channel $channel = null): string
    {
        return json_encode(
            $this->buildManifest($channel, 1),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
    }

    public function exportZip(?Channel $channel = null): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new \RuntimeException('PHP Zip extension is required for archive export.');
        }

        $channel ??= $this->homeSectionService->channel();
        $sections = $this->buildManifest($channel, 2)['sections'];
        $assets = $this->collectAssetsFromSections($sections);
        $manifest = $this->buildManifest($channel, 2);

        $tempDir = storage_path('app/temp/storefront-export-'.Str::random(8));
        File::ensureDirectoryExists($tempDir.'/'.self::ASSETS_DIR);

        foreach ($assets as $diskPath => $absolute) {
            $target = $tempDir.'/'.self::ASSETS_DIR.'/'.$diskPath;
            File::ensureDirectoryExists(dirname($target));
            File::copy($absolute, $target);
        }

        file_put_contents(
            $tempDir.'/'.self::MANIFEST_FILE,
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $zipPath = storage_path('app/beyondary-storefront-'.$channel->code.'-'.now()->format('Ymd-His').'.zip');
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create export archive.');
        }

        $this->addDirectoryToZip($zip, $tempDir, '');
        $zip->close();

        File::deleteDirectory($tempDir);

        return $zipPath;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function importPayload(array $payload, Channel $targetChannel, bool $replaceExisting = true, ?string $assetsRoot = null): int
    {
        $version = (int) ($payload['version'] ?? 0);

        if (! in_array($version, [1, 2], true) || empty($payload['sections'])) {
            throw new \InvalidArgumentException('Invalid storefront export file.');
        }

        if ($assetsRoot) {
            $assetsRoot = rtrim($assetsRoot, '/\\');
        }

        if ($replaceExisting) {
            $this->deleteHomepageSections($targetChannel);
        }

        $imported = 0;

        foreach ($payload['sections'] as $section) {
            Event::dispatch('theme_customization.create.before');

            $theme = $this->themeCustomizationRepository->create([
                'name' => $section['name'],
                'type' => $section['type'],
                'sort_order' => $section['sort_order'] ?? 0,
                'status' => $section['status'] ?? true,
                'channel_id' => $targetChannel->id,
                'theme_code' => $targetChannel->theme,
            ]);

            foreach ($section['translations'] ?? [] as $locale => $translation) {
                $options = $translation['options'] ?? [];
                $options = $this->remapOptionsAssets($options, $theme->id, $assetsRoot);

                $model = $theme->translateOrNew($locale);
                $model->options = $options;
                $model->theme_customization_id = $theme->id;
                $model->save();
            }

            Event::dispatch('theme_customization.create.after', $theme);
            $imported++;
        }

        return $imported;
    }

    public function importZip(string $zipPath, Channel $targetChannel, bool $replaceExisting = true): int
    {
        if (! class_exists(ZipArchive::class)) {
            throw new \RuntimeException('PHP Zip extension is required for archive import.');
        }

        $tempDir = storage_path('app/temp/storefront-import-'.Str::random(8));
        File::ensureDirectoryExists($tempDir);

        $zip = new ZipArchive;

        if ($zip->open($zipPath) !== true) {
            throw new \InvalidArgumentException('Unable to open import archive.');
        }

        $zip->extractTo($tempDir);
        $zip->close();

        $manifestPath = $this->findManifestPath($tempDir);

        if (! $manifestPath) {
            File::deleteDirectory($tempDir);

            throw new \InvalidArgumentException('manifest.json not found in archive.');
        }

        $payload = json_decode(file_get_contents($manifestPath), true);
        $assetsRoot = dirname($manifestPath).'/'.self::ASSETS_DIR;

        if (! is_dir($assetsRoot)) {
            $assetsRoot = null;
        }

        try {
            return $this->importPayload($payload, $targetChannel, $replaceExisting, $assetsRoot);
        } finally {
            File::deleteDirectory($tempDir);
        }
    }

    public function installDefaultPreset(Channel $targetChannel, bool $replaceExisting = true): int
    {
        return $this->importPayload(
            $this->buildDefaultPreset(),
            $targetChannel,
            $replaceExisting
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function buildDefaultPreset(): array
    {
        $sections = [];

        foreach (HomeSectionService::SECTIONS as $key => $meta) {
            $translations = [];

            foreach (['en', 'th'] as $locale) {
                $translations[$locale]['options'] = $this->defaultOptionsForSection($key, $locale);
            }

            $sections[] = [
                'type' => $meta['type'],
                'name' => $meta['default_name'],
                'sort_order' => $meta['sort_order'],
                'status' => true,
                'translations' => $translations,
            ];
        }

        return [
            'version' => 2,
            'theme_code' => 'beyondary',
            'channel_code' => 'preset',
            'exported_at' => now()->toIso8601String(),
            'preset' => 'beyondary-homepage',
            'sections' => $sections,
            'assets' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultOptionsForSection(string $key, string $locale): array
    {
        return match ($key) {
            'hero' => [
                'images' => [[
                    'title' => trans('beyondary.hero.title', [], $locale),
                    'link' => '#shop',
                    'image' => 'themes/shop/beyondary/images/hero.jpg',
                ]],
            ],
            'trust' => [
                'services' => [
                    [
                        'title' => trans('beyondary.trust.shipping_title', [], $locale),
                        'description' => trans('beyondary.trust.shipping_desc', [], $locale),
                        'service_icon' => 'icon-truck',
                    ],
                    [
                        'title' => trans('beyondary.trust.authentic_title', [], $locale),
                        'description' => trans('beyondary.trust.authentic_desc', [], $locale),
                        'service_icon' => 'icon-product',
                    ],
                    [
                        'title' => trans('beyondary.trust.payment_title', [], $locale),
                        'description' => trans('beyondary.trust.payment_desc', [], $locale),
                        'service_icon' => 'icon-dollar-sign',
                    ],
                ],
            ],
            'categories' => [
                'title' => trans('beyondary.categories.title', [], $locale),
                'filters' => ['parent_id' => 1, 'sort' => 'asc', 'limit' => 4],
            ],
            'products' => [
                'title' => trans('beyondary.home.featured_title', [], $locale),
                'filters' => ['sort' => 'created_at-desc', 'limit' => 8],
            ],
            'our_story' => [
                'html' => $this->homeSectionService->buildOurStoryHtml(
                    $this->homeSectionService->defaultOurStoryFields($locale)
                ),
                'css' => '',
            ],
            'menu' => $this->homeSectionService->defaultNavigationFields($locale),
            'footer' => $this->homeSectionService->defaultFooterFields($locale),
            default => [],
        };
    }

    protected function deleteHomepageSections(Channel $targetChannel): void
    {
        $existing = $this->themeCustomizationRepository->findWhere([
            'channel_id' => $targetChannel->id,
            'theme_code' => $targetChannel->theme,
        ]);

        foreach ($existing as $theme) {
            if (! $this->homeSectionService->isManagedSection($theme)) {
                continue;
            }

            Event::dispatch('theme_customization.delete.before', $theme->id);
            $this->themeCustomizationRepository->delete($theme->id);
            Storage::disk('public')->deleteDirectory('theme/'.$theme->id);
            Event::dispatch('theme_customization.delete.after', $theme->id);
        }
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    protected function remapOptionsAssets(array $options, int $newThemeId, ?string $assetsRoot): array
    {
        $encoded = json_encode($options, JSON_UNESCAPED_UNICODE);
        $paths = $this->extractStoragePaths($options);

        foreach ($paths as $oldPath) {
            if (! preg_match('#^theme/(\d+)/(.+)$#', $oldPath, $matches)) {
                continue;
            }

            $filename = $matches[2];
            $newPath = 'theme/'.$newThemeId.'/'.$filename;
            $newReference = 'storage/'.$newPath;

            if ($assetsRoot) {
                $source = $assetsRoot.'/'.$oldPath;

                if (is_file($source)) {
                    Storage::disk('public')->makeDirectory('theme/'.$newThemeId);
                    Storage::disk('public')->put($newPath, file_get_contents($source));
                }
            }

            $encoded = str_replace($oldPath, $newPath, $encoded);
            $encoded = str_replace('storage/'.$oldPath, $newReference, $encoded);
            $encoded = preg_replace(
                '#https?://[^"\']+/storage/'.preg_quote($oldPath, '#').'#',
                $newReference,
                $encoded
            ) ?? $encoded;
        }

        return json_decode($encoded, true) ?? $options;
    }

    /**
     * @param  array<string, mixed>  $options
     * @return Collection<int, string>
     */
    protected function extractStoragePaths(array $options): Collection
    {
        $json = json_encode($options, JSON_UNESCAPED_UNICODE) ?: '';

        preg_match_all('#(?:https?://[^"\']+/)?storage/(theme/\d+/[^"\']+)#', $json, $storageMatches);
        preg_match_all('#(?<!storage/)(theme/\d+/[^"\']+\.(?:webp|jpg|jpeg|png|svg))#', $json, $directMatches);

        return collect($storageMatches[1] ?? [])
            ->merge($directMatches[1] ?? [])
            ->unique()
            ->values();
    }

    protected function absolutePathForDisk(string $diskPath): ?string
    {
        if (Storage::disk('public')->exists($diskPath)) {
            return Storage::disk('public')->path($diskPath);
        }

        return null;
    }

    protected function findManifestPath(string $directory): ?string
    {
        $direct = $directory.'/'.self::MANIFEST_FILE;

        if (is_file($direct)) {
            return $direct;
        }

        foreach (File::allFiles($directory) as $file) {
            if ($file->getFilename() === self::MANIFEST_FILE) {
                return $file->getPathname();
            }
        }

        return null;
    }

    protected function addDirectoryToZip(ZipArchive $zip, string $directory, string $zipPrefix): void
    {
        foreach (File::allFiles($directory) as $file) {
            $relative = ltrim(str_replace($directory, '', $file->getPathname()), '/\\');
            $zip->addFile($file->getPathname(), $zipPrefix.$relative);
        }
    }
}
