<?php

namespace App\Http\Controllers;

use App\Http\Requests\Resource\CreateResourceFromFileRequest;
use App\Http\Requests\Resource\CreateResourceRequest;
use App\Http\Requests\Resource\DeleteResourceRequest;
use App\Http\Requests\Resource\GetResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Http\Resources\ResourceResource;
use App\Http\Services\ResourceService;
use App\Models\Resource;

class ResourceController extends Controller
{
    protected $resourceService;

    public function __construct(ResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ResourceResource::collection(auth()->user()->resources);
    }

    /**
     * @param CreateResourceRequest $request
     * @return ResourceResource
     */
    public function store(CreateResourceRequest $request)
    {
        $resource = $this->resourceService->create($request);

        return new ResourceResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateResourceFromFileRequest $request
     * @return ResourceResource
     */
    public function storeFromFile(CreateResourceFromFileRequest $request)
    {
        $resource = $this->resourceService->createFromUploadedFile($request);

        return new ResourceResource($resource);
    }

    /**
     * @param GetResourceRequest $request
     * @param Resource $resource
     * @return ResourceResource
     */
    public function show(GetResourceRequest $request, Resource $resource)
    {
        return new ResourceResource($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateResourceRequest $request
     * @param  Resource $resource
     * @return ResourceResource
     */
    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        $resource = $this->resourceService->update($request, $resource);

        return new ResourceResource($resource);
    }

    /**
     * @param DeleteResourceRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteResourceRequest $request, $id)
    {
        $result = (int) $this->resourceService->delete($id);

        return response()->json(['status' => $result]);
    }
}
