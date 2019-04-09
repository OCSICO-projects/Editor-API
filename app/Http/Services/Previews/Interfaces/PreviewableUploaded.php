<?php

namespace App\Http\Services\Previews\Interfaces;

use App\Models\File;

interface PreviewableUploaded extends BelongsToResource
{
    /**
     * @param $file
     * @param $fileModel
     * @return File
     */
    public function create($file, File $fileModel);
    // todo uncomment when realize all uploaded subtypes
//    public function create($file, File $fileModel) : File;
}