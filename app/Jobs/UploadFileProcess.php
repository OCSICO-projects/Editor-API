<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadFileProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $content;
    public $fileModel;
    public $path;
    public $subtype;

    /**
     * Create a new job instance.
     *
     * @param $content
     * @param $fileModel
     * @param $subtype
     */
    public function __construct($content, $fileModel, $subtype)
    {
        $this->content = base64_encode($content);
        $this->fileModel = $fileModel;
        $this->path = $fileModel->path;
        $this->subtype = $subtype;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Storage::put($this->path, base64_decode($this->content));
        // todo implement upload to cloud storage
    }
}
