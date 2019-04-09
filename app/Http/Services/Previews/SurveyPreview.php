<?php

namespace App\Http\Services\Previews;

use App\Http\Services\Previews\Interfaces\Previewable;
use App\Models\File;
use App\Models\Resource;

class SurveyPreview extends Preview implements Previewable
{
    /**
     * @param Resource $resource
     * @return File
     */
    public function create(Resource $resource)
    {
        // todo implement real preview
        return null;
    }
}