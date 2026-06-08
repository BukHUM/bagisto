<?php

namespace Beyondary\Storefront\Services;

use Illuminate\Support\Facades\Cache;
use Webkul\Core\Models\CoreConfig;

class AdminThemeSettingsService
{
    public const CONFIG_CODE = 'beyondary.admin_theme.settings';

    /**
     * @var array<string, array<string, mixed>>|null
     */
    protected ?array $resolvedSettings = null;

    /**
     * @return array<string, array<string, mixed>>
     */
    public function defaults(): array
    {
        return [
            'typography' => [
                'font_sans' => 'Prompt',
                'font_display' => 'Playfair Display',
                'base_size' => '14',
            ],
            'colors' => [
                'primary' => '#B88B54',
                'primary_hover' => '#9A7345',
                'sidebar' => '#2E2720',
                'surface' => '#F8F6F0',
                'text' => '#3A2618',
                'muted' => '#6B4A31',
                'border' => '#E5DFD4',
            ],
            'layout' => [
                'density' => 'comfortable',
                'corner_radius' => 'sm',
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function get(bool $fresh = false): array
    {
        if (! $fresh && $this->resolvedSettings !== null) {
            return $this->resolvedSettings;
        }

        $stored = CoreConfig::query()
            ->where('code', self::CONFIG_CODE)
            ->whereNull('channel_code')
            ->whereNull('locale_code')
            ->value('value');

        if (! is_string($stored) || $stored === '') {
            return $this->resolvedSettings = $this->defaults();
        }

        $decoded = json_decode($stored, true);

        if (! is_array($decoded)) {
            return $this->resolvedSettings = $this->defaults();
        }

        return $this->resolvedSettings = array_replace_recursive($this->defaults(), $decoded);
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, array<string, mixed>>
     */
    public function save(array $input): array
    {
        $settings = $this->sanitize($input);

        CoreConfig::query()->updateOrCreate(
            [
                'code' => self::CONFIG_CODE,
                'channel_code' => null,
                'locale_code' => null,
            ],
            ['value' => json_encode($settings)]
        );

        Cache::forget('core_config');
        $this->resolvedSettings = $settings;

        return $settings;
    }

    /**
     * @return array<string, array<string, array{label: string, weights: string, category: string}>>
     */
    public function fontOptions(): array
    {
        return [
            'sans' => [
                'Prompt' => [
                    'label' => 'Prompt',
                    'weights' => '300;400;500;600;700',
                    'category' => 'sans',
                ],
                'Google Sans Text' => [
                    'label' => 'Google Sans Text',
                    'weights' => '400;500;600;700',
                    'category' => 'sans',
                ],
                'Inter' => [
                    'label' => 'Inter',
                    'weights' => '400;500;600;700',
                    'category' => 'sans',
                ],
                'Roboto' => [
                    'label' => 'Roboto',
                    'weights' => '400;500;700',
                    'category' => 'sans',
                ],
                'Open Sans' => [
                    'label' => 'Open Sans',
                    'weights' => '400;500;600;700',
                    'category' => 'sans',
                ],
                'Noto Sans Thai' => [
                    'label' => 'Noto Sans Thai',
                    'weights' => '400;500;600;700',
                    'category' => 'sans',
                ],
                'IBM Plex Sans Thai' => [
                    'label' => 'IBM Plex Sans Thai',
                    'weights' => '400;500;600;700',
                    'category' => 'sans',
                ],
            ],
            'display' => [
                'Playfair Display' => [
                    'label' => 'Playfair Display',
                    'weights' => '500;600;700',
                    'category' => 'display',
                ],
                'Noto Serif Thai' => [
                    'label' => 'Noto Serif Thai',
                    'weights' => '400;500;600;700',
                    'category' => 'display',
                    'supports_thai' => true,
                ],
                'Trirong' => [
                    'label' => 'Trirong',
                    'weights' => '500;600;700',
                    'category' => 'display',
                    'supports_thai' => true,
                ],
                'Maitree' => [
                    'label' => 'Maitree',
                    'weights' => '400;500;600;700',
                    'category' => 'display',
                    'supports_thai' => true,
                ],
                'Pridi' => [
                    'label' => 'Pridi',
                    'weights' => '500;600;700',
                    'category' => 'display',
                    'supports_thai' => true,
                ],
                'Sarabun' => [
                    'label' => 'Sarabun',
                    'weights' => '500;600;700',
                    'category' => 'display',
                    'supports_thai' => true,
                ],
            ],
        ];
    }

    public function googleFontFamilyUrl(string $family, string $weights): string
    {
        $encoded = str_replace(' ', '+', $family);

        return 'https://fonts.googleapis.com/css2?family='.$encoded.':wght@'.$weights.'&display=swap';
    }

    /**
     * @param  array<string, array<string, mixed>>  $settings
     * @return array<string, array<string, array{label: string, weights: string, category: string}>>
     */
    public function fontOptionsForForm(array $settings): array
    {
        $options = $this->fontOptions();

        foreach (['sans' => 'font_sans', 'display' => 'font_display'] as $group => $key) {
            $current = (string) ($settings['typography'][$key] ?? '');

            if ($current !== '' && ! isset($options[$group][$current])) {
                $options[$group] = [
                    $current => [
                        'label' => $current.' (saved)',
                        'weights' => $group === 'display' ? '500;600;700' : '400;500;600;700',
                        'category' => $group,
                    ],
                ] + $options[$group];
            }
        }

        return $options;
    }

    public function googleFontsUrl(array $settings): string
    {
        $options = $this->fontOptions();
        $display = (string) ($settings['typography']['font_display'] ?? 'Playfair Display');
        $sans = (string) ($settings['typography']['font_sans'] ?? 'Prompt');

        $displayMeta = $options['display'][$display] ?? ['weights' => '500;600;700'];
        $sansMeta = $options['sans'][$sans] ?? ['weights' => '400;500;600;700'];

        $families = [
            str_replace(' ', '+', $display).':wght@'.$displayMeta['weights'],
            str_replace(' ', '+', $sans).':wght@'.$sansMeta['weights'],
        ];

        return 'https://fonts.googleapis.com/css2?family='.implode('&family=', $families).'&display=swap';
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, array<string, mixed>>
     */
    protected function sanitize(array $input): array
    {
        $defaults = $this->defaults();
        $fontOptions = $this->fontOptions();
        $sansOptions = array_keys($fontOptions['sans']);
        $displayOptions = array_keys($fontOptions['display']);

        $fontSans = (string) ($input['typography']['font_sans'] ?? $defaults['typography']['font_sans']);
        $fontDisplay = (string) ($input['typography']['font_display'] ?? $defaults['typography']['font_display']);

        if (! in_array($fontSans, $sansOptions, true)) {
            $fontSans = $defaults['typography']['font_sans'];
        }

        if (! in_array($fontDisplay, $displayOptions, true)) {
            $fontDisplay = $defaults['typography']['font_display'];
        }

        $baseSize = (int) ($input['typography']['base_size'] ?? $defaults['typography']['base_size']);
        $baseSize = max(12, min(18, $baseSize));

        return [
            'typography' => [
                'font_sans' => $fontSans,
                'font_display' => $fontDisplay,
                'base_size' => (string) $baseSize,
            ],
            'colors' => [
                'primary' => $this->sanitizeHex($input['colors']['primary'] ?? null, $defaults['colors']['primary']),
                'primary_hover' => $this->sanitizeHex($input['colors']['primary_hover'] ?? null, $defaults['colors']['primary_hover']),
                'sidebar' => $this->sanitizeHex($input['colors']['sidebar'] ?? null, $defaults['colors']['sidebar']),
                'surface' => $this->sanitizeHex($input['colors']['surface'] ?? null, $defaults['colors']['surface']),
                'text' => $this->sanitizeHex($input['colors']['text'] ?? null, $defaults['colors']['text']),
                'muted' => $this->sanitizeHex($input['colors']['muted'] ?? null, $defaults['colors']['muted']),
                'border' => $this->sanitizeHex($input['colors']['border'] ?? null, $defaults['colors']['border']),
            ],
            'layout' => [
                'density' => in_array($input['layout']['density'] ?? '', ['comfortable', 'compact'], true)
                    ? $input['layout']['density']
                    : $defaults['layout']['density'],
                'corner_radius' => in_array($input['layout']['corner_radius'] ?? '', ['sm', 'md'], true)
                    ? $input['layout']['corner_radius']
                    : $defaults['layout']['corner_radius'],
            ],
        ];
    }

    protected function sanitizeHex(mixed $value, string $fallback): string
    {
        if (! is_string($value)) {
            return $fallback;
        }

        $value = strtoupper(trim($value));

        if (preg_match('/^#([A-F0-9]{6})$/', $value) !== 1) {
            return $fallback;
        }

        return $value;
    }
}
