<?php

namespace App\Http\Services\Previews;

use App\Http\Services\FileService;
use App\Http\Services\Previews\Interfaces\Previewable;
use App\Models\File;
use App\Models\Resource;
use Exception;
use Illuminate\Support\Facades\Storage;

class YoutubePreview extends Preview implements Previewable
{
    private $youtubeId;

    /**
     * @param Resource $resource
     * @return File|null
     */
    public function create(Resource $resource)
    {
        $file = $this->getYoutubeThumbnail($resource->url);
        if ($file) {
            $image = $this->resizeImage($file);

            $directory = FileService::getPreviewDirBySubtypeAndType($resource->subtype, $resource->type);
            $name = $this->youtubeId . time() . '.jpg';
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

    /**
     * @param $url
     * @return mixed
     */
    private function getYoutubeThumbnail($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        $youtubeId = $this->youtubeId = $match[1];

        try {
            $preview = file_get_contents("https://img.youtube.com/vi/$youtubeId/0.jpg");
        } catch (Exception $e) {
            $preview = null;
        }

        return $preview !== false || $preview !== false ? $preview : null;
    }
}
