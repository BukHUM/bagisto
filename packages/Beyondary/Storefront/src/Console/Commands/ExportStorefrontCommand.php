<?php

namespace Beyondary\Storefront\Console\Commands;

use Beyondary\Storefront\Services\StorefrontTransferService;
use Illuminate\Console\Command;
use Webkul\Core\Repositories\ChannelRepository;

class ExportStorefrontCommand extends Command
{
    protected $signature = 'beyondary:storefront:export
                            {--channel= : Channel code (default: current channel)}
                            {--output= : Output file path}
                            {--format=zip : zip (with assets) or json}';

    protected $description = 'Export beyondary storefront sections (ZIP with images or JSON)';

    public function handle(StorefrontTransferService $transferService, ChannelRepository $channelRepository): int
    {
        $channelCode = $this->option('channel') ?: core()->getCurrentChannelCode();
        $channel = $channelRepository->findOneByField('code', $channelCode);

        if (! $channel) {
            $this->error("Channel [{$channelCode}] not found.");

            return self::FAILURE;
        }

        $format = $this->option('format') ?: 'zip';

        if ($format === 'json') {
            $path = $this->option('output') ?: storage_path('app/beyondary-storefront-'.$channel->code.'.json');
            file_put_contents($path, $transferService->exportJson($channel));
            $this->info("Exported JSON for [{$channel->code}] to {$path}");

            return self::SUCCESS;
        }

        try {
            $path = $transferService->exportZip($channel);
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if ($output = $this->option('output')) {
            rename($path, $output);
            $path = $output;
        }

        $this->info("Exported ZIP for [{$channel->code}] to {$path}");

        return self::SUCCESS;
    }
}
