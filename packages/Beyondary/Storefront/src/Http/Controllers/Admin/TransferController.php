<?php

namespace Beyondary\Storefront\Http\Controllers\Admin;

use Beyondary\Storefront\Services\StorefrontTransferService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\ChannelRepository;

class TransferController extends Controller
{
    public function __construct(
        protected StorefrontTransferService $transferService,
        protected ChannelRepository $channelRepository
    ) {}

    public function export(): StreamedResponse|BinaryFileResponse
    {
        $format = request('format', 'zip');

        if ($format === 'json') {
            $manifest = $this->transferService->buildManifest(version: 1);
            $filename = sprintf(
                'beyondary-storefront-%s-%s.json',
                $manifest['channel_code'],
                now()->format('Ymd-His')
            );

            return response()->streamDownload(
                fn () => print($this->transferService->exportJson()),
                $filename,
                ['Content-Type' => 'application/json']
            );
        }

        $zipPath = $this->transferService->exportZip();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function import(): RedirectResponse
    {
        request()->validate([
            'import_file' => 'required|file|mimes:json,txt,zip',
            'channel_id' => 'required|integer',
        ]);

        $channel = $this->channelRepository->findOrFail((int) request('channel_id'));
        $file = request()->file('import_file');
        $replace = request()->boolean('replace_existing', true);

        if ($file->getClientOriginalExtension() === 'zip') {
            $count = $this->transferService->importZip($file->getRealPath(), $channel, $replace);
        } else {
            $payload = json_decode(file_get_contents($file->getRealPath()), true);
            $count = $this->transferService->importPayload($payload, $channel, $replace);
        }

        session()->flash('success', trans('beyondary-storefront::app.transfer.imported', ['count' => $count]));

        return redirect()->route('admin.beyondary.storefront.index');
    }

    public function installPreset(): RedirectResponse
    {
        request()->validate([
            'channel_id' => 'required|integer',
        ]);

        $channel = $this->channelRepository->findOrFail((int) request('channel_id'));

        $count = $this->transferService->installDefaultPreset(
            $channel,
            replaceExisting: request()->boolean('replace_existing', true)
        );

        session()->flash('success', trans('beyondary-storefront::app.transfer.preset-installed', ['count' => $count]));

        return redirect()->route('admin.beyondary.storefront.index');
    }
}
