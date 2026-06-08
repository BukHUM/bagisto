<?php

namespace Beyondary\Storefront\Console\Commands;

use Beyondary\Storefront\Services\StorefrontTransferService;
use Illuminate\Console\Command;
use Webkul\Core\Repositories\ChannelRepository;

class ImportStorefrontCommand extends Command
{
    protected $signature = 'beyondary:storefront:import
                            {file : Path to ZIP or JSON export}
                            {--channel= : Target channel code}
                            {--keep-existing : Do not replace existing homepage sections}';

    protected $description = 'Import beyondary storefront sections (ZIP copies theme images)';

    public function handle(StorefrontTransferService $transferService, ChannelRepository $channelRepository): int
    {
        $path = $this->argument('file');

        if (! is_readable($path)) {
            $this->error("File not readable: {$path}");

            return self::FAILURE;
        }

        if (str_ends_with(strtolower($path), '.zip')) {
            $channelCode = $this->option('channel') ?: core()->getCurrentChannelCode();
        } else {
            $payload = json_decode(file_get_contents($path), true);
            $channelCode = $this->option('channel')
                ?: ($payload['channel_code'] ?? core()->getCurrentChannelCode());
        }

        $channel = $channelRepository->findOneByField('code', $channelCode);

        if (! $channel) {
            $this->error("Channel [{$channelCode}] not found.");

            return self::FAILURE;
        }

        $replace = ! $this->option('keep-existing');

        try {
            if (str_ends_with(strtolower($path), '.zip')) {
                $count = $transferService->importZip($path, $channel, $replace);
            } else {
                $count = $transferService->importPayload($payload, $channel, $replace);
            }
        } catch (\InvalidArgumentException|\RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Imported {$count} sections into channel [{$channel->code}].");

        return self::SUCCESS;
    }
}
