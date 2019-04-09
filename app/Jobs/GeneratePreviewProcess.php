<?php

namespace App\Jobs;

use App\Http\Services\Previews\PreviewUploadedFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePreviewProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $content;
    public $fileModel;
    public $resourceModel;
    public $subtype;

    /**
     * Create a new job instance.
     *
     * @param $content
     * @param $fileModel
     * @param $subtype
     * @param $resourceModel
     */
    public function __construct($content, $fileModel, $subtype, $resourceModel)
    {
        $this->content = base64_encode($content);
        $this->fileModel = $fileModel;
        $this->subtype = $subtype;
        $this->resourceModel = $resourceModel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $previewFactory = PreviewUploadedFactory::make($this->subtype);
        $previewModel = $previewFactory->create(base64_decode($this->content), $this->fileModel);

        if ($previewModel) {
            $previewFactory->updateResourcePreview($this->resourceModel, $previewModel);
        }
    }
}
