<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'directory' => $this->directory,
            'url' => $this->url,
            'filename' => $this->filename,
            'original_filename' => $this->original_filename,
            'extension' => $this->extension,
            'media_type' => $this->media_type,
            'size' => $this->size,
            'duration' => $this->duration,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
