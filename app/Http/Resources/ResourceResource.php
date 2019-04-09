<?php

namespace App\Http\Resources;

use App\Enums\ResourceSubtypes;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
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
            'folder_id' => $this->folder_id,
            'file' => (new FileResource($this->file()->first())),
            'preview_id' => $this->preview_id,
            'preview' => $this->preview ? $this->preview->url : asset(config('thumbnail.default_img')),
            'version' => $this->version,
            'name' => $this->name,
            $this->mergeWhen($this->children, ['relates' => ResourceRelationResource::collection($this->children)]),
            'type' => $this->type,
            'subtype' => $this->subtype,
            $this->mergeWhen($this->subtype === ResourceSubtypes::YOUTUBE, ['url' => $this->url]),
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
