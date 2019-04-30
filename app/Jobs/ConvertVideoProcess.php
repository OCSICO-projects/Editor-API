<?php

namespace App\Jobs;

use App\Events\VideoConverted;
use App\Models\File;
use App\Models\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertVideoProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileModel;

    /**
     * Create a new job instance.
     *
     * @param $fileModel
     */
    public function __construct(File $fileModel)
    {
        $this->fileModel = $fileModel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $storage_path = storage_path('app/' . config('filesystems.default'));
        $old_path = $storage_path . $this->fileModel->path;
        $new_path = $old_path . '.encoded.mp4';

        shell_exec("ffmpeg -i $old_path -f mp4 -c:v libx264 -crf 23 -profile:v baseline -level 3.0 -pix_fmt yuv420p -c:a aac -ac 2 -b:a 128k -movflags faststart $new_path -hide_banner -y");

        $this->fileModel->filename = $this->fileModel->filename . '.encoded.mp4';
        $this->fileModel->path = $this->fileModel->path . '.encoded.mp4';
        $this->fileModel->media_type = 'video/mp4';
        $this->fileModel->extension = 'mp4';
        $this->fileModel->save();

        event(new VideoConverted($this->fileModel));
    }
}
