<?php

namespace App\Http\Services\Previews\Interfaces;

use App\Models\File;
use App\Models\Resource;

interface BelongsToResource
{
    /**
     * @param Resource $resource
     * @param File $preview
     */
    public function updateResourcePreview(Resource $resource, File $preview);
}