<?php

namespace App\Http\Services\Previews;

use App\Models\File;
use App\Models\Resource;
use Intervention\Image\Facades\Image;

abstract class Preview
{
    /** @var File */
    public $model;

    /**
     * @param $file
     * @return mixed
     */
    public function resizeImage($file)
    {
        return Image::make($file)->resize(null, config('thumbnail.dimensions.height'), function ($constraint) {
            $constraint->aspectRatio();
        });
    }

    /**
     * @param Resource $resource
     * @param File $preview
     */
    public function updateResourcePreview(Resource $resource, File $preview)
    {
        $resource->update([
            'preview_id' => $preview->id
        ]);
    }
}
