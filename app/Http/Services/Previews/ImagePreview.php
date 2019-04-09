<?php

namespace App\Http\Services\Previews;

use App\Http\Services\Previews\Interfaces\PreviewableUploaded;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class ImagePreview extends Preview implements PreviewableUploaded
{
    /**
     * @param $file
     * @param $fileModel
     *
     * @return File
     */
    public function create($file, File $fileModel): File
    {
        $image = $this->resizeImage($file);

        /** @var File $file */
        $previewModel = File::create([
            'original_id' => $fileModel->id,
            'disk' => $fileModel->disk,
            'url' => config('app.url') . $fileModel->directory . 'preview/' . $fileModel->filename,
            'path' => $fileModel->directory . 'preview/' . $fileModel->filename,
            'directory' => $fileModel->directory . 'preview/',
            'filename' => $fileModel->filename,
            'original_filename' => $fileModel->original_filename,
            'extension' => $fileModel->extension,
            'media_type' => $image->mime(),
            'size' => strlen((string) $image->encode())
        ]);

        Storage::put($previewModel->path, $image->stream());

        return $previewModel;
    }
}