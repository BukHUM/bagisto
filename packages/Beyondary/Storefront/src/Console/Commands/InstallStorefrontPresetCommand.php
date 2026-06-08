<?php

namespace Beyondary\Storefront\Console\Commands;

use Beyondary\Storefront\Services\StorefrontTransferService;
use Illuminate\Console\Command;
use Webkul\Core\Repositories\ChannelRepository;

class InstallStorefrontPresetCommand extends Command
{
    protected $signature = 'beyondary:storefront:install-preset
                            {--channel= : Target channel code}
                            {--keep-existing : Do not replace existing homepage sections}';

    protected $description = 'Install the built-in beyondary homepage preset for a channel';

    public function handle(StorefrontTransferService $transferService, ChannelRepository $channelRepository): int
    {
        $channelCode = $this->option('channel') ?: core()->getCurrentChannelCode();
        $channel = $channelRepository->findOneByField('code', $channelCode);

        if (! $channel) {
            $this->error("Channel [{$channelCode}] not found.");

            return self::FAILURE;
        }

        if ($channel->theme !== 'beyondary') {
            $this->warn("Channel [{$channel->code}] uses theme [{$channel->theme}], not beyondary.");
        }

        $count = $transferService->installDefaultPreset(
            $channel,
            replaceExisting: ! $this->option('keep-existing')
        );

        $this->info("Installed preset ({$count} sections) on channel [{$channel->code}].");

        return self::SUCCESS;
    }
}
