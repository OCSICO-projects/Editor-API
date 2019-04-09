<?php

namespace App\Http\Services\Previews\Interfaces;

use App\Models\Resource;

interface Previewable extends BelongsToResource
{
    /**
     * @param Resource $resource
     */
    public function create(Resource $resource);
}