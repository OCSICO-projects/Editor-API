<?php

namespace App\Http\Services\Previews;

use App\Http\Services\FileService;
use App\Http\Services\Previews\Interfaces\Previewable;
use App\Models\File;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class SlidePreview extends Preview implements Previewable
{
    /**
     * @param Resource $resource
     * @return File
     */
    public function create(Resource $resource)
    {
        $image = $this->resizeImage($resource->thumbnail);

        $directory = FileService::getPreviewDirBySubtypeAndType($resource->subtype, $resource->type);
        $name = $resource->id . '.jpg';
        $path = $directory . $name;

        /** @var File $file */
        $previewModel = File::create([
            'disk' => config('filesystems.default'),
            'url' => config('app.url') . $path,
            'path' => $path,
            'directory' => $directory,
            'filename' => $name,
            'original_filename' => $name,
            'extension' => 'jpg',
            'media_type' => $image->mime(),
            'size' => strlen((string) $image->encode())
        ]);

        Storage::put($path, $image->stream());

        return $previewModel;
    }
}