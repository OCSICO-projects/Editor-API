<?php

namespace App\Http\Services\Previews;

use App\Http\Services\Previews\Interfaces\PreviewableUploaded;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Lakshmaji\Thumbnail\Facade\Thumbnail;

class VideoPreview extends Preview implements PreviewableUploaded
{
    /**
     * @param $file
     * @param $fileModel
     *
     * @return File
     */
    public function create($file, File $fileModel)
    {
        $original_filename = $this->trimEncodedNamePart($fileModel->original_filename);
        $filename = $this->trimEncodedNamePart($fileModel->filename);
        $path = $this->trimEncodedNamePart($fileModel->path);

        $thumbnailDirectory = $fileModel->directory . 'preview/';
        $thumbnailFilename = $filename . '.jpg';
        $fullPathThumbnail = storage_path('app/' . config('filesystems.default') . $thumbnailDirectory);

        if (!is_dir($fullPathThumbnail)) {
            mkdir($fullPathThumbnail);
        }

        # Generate video preview (Resize is not working)
        Thumbnail::getThumbnail(
            storage_path('app/' . config('filesystems.default') . $path),
            $fullPathThumbnail,
            $thumbnailFilename,
            0
        );
        # Get preview file
        $previewFile = Storage::get($thumbnailDirectory . $thumbnailFilename);
        # Make resized image
        $image =
            Image::make($previewFile)
                ->resize(null, config('thumbnail.dimensions.height'), function ($constraint) {
                    $constraint->aspectRatio();
                });
        # Save resized image replacing the generated video preview
        Storage::put($thumbnailDirectory . $thumbnailFilename, $image->stream());

        /** @var File $file */
        $previewModel = File::create([
            'original_id' => $fileModel->id,
            'disk' => $fileModel->disk,
            'url' => config('app.url') . $thumbnailDirectory . $thumbnailFilename,
            'path' => $thumbnailDirectory  . $thumbnailFilename,
            'directory' => $thumbnailDirectory,
            'filename' => $thumbnailFilename,
            'original_filename' => $thumbnailFilename,
            'extension' => 'jpg',
            'media_type' => $image->mime(),
            'size' => strlen((string) $image->encode())
        ]);

        return $previewModel;
    }

    private function trimEncodedNamePart($string)
    {
        return str_replace('.encoded.mp4', '', $string);
    }
}
