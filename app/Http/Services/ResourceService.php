<?php

namespace App\Http\Services;

use App\Enums\ResourceSubtypes;
use App\Enums\ResourceTypes;
use App\Models\Resource;

class ResourceService
{
    /**
     * @param $data
     *
     * @return Resource
     */
    public function create($data)
    {
        $resource = new Resource($data->all());
        $resource->user_id = auth()->user()->id;
        $resource->save();

        $resource->children()->sync($data->relates);

        $fileService = new FileService();
        $preview = $fileService->createPreviewFromResource($resource);

        if ($preview) {
            $resource->update([
                'preview_id' => $preview->id
            ]);
        }

        return $resource;
    }

    /**
     * @param $request
     *
     * @return Resource
     */
    public function createFromUploadedFile($request)
    {
        $fileService = new FileService();
        $fileModel = $fileService->create($request);

        $resource = Resource::create([
            'folder_id' => $request->folder_id,
            'user_id' => $fileModel->user_id,
            'file_id' => $fileModel->id,
            'name' => $fileModel->original_filename,
            'type' => ResourceTypes::FILE,
            'subtype' => $fileService->subtype,
        ]);

        $fileService->generatePreview($fileModel, $resource);

        return $resource;
    }

    /**
     * @param $data
     * @param Resource $resource
     * @return Resource
     */
    public function update($data, Resource $resource)
    {
        $resource->update($data->all());

        if (in_array($resource->subtype, ResourceSubtypes::getAllNotUploadableAndRenderable())) {
            $resource->children()->sync($data->relates);
            $this->updatePreviewForComposeType($resource);
        }

        return $resource->fresh();
    }

    /**
     * @param Resource $resource
     */
    private function updatePreviewForComposeType(Resource $resource)
    {
        $fileService = new FileService();
        $preview = $fileService->createPreviewFromResource($resource);

        if ($preview) {
            if ($resource->preview) {
                $fileService->deleteModel($resource->preview);
            }

            $resource->update([
                'preview_id' => $preview->id
            ]);
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $resource = Resource::find($id);
        $fileService = new FileService();

        if ($resource->file) {
            $fileService->delete($resource->file);
        }

        if ($resource->preview) {
            $fileService->delete($resource->preview);
        }

        return $resource->delete();
    }
}
