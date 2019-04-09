<?php

namespace App\Http\Controllers;

use App\Http\Requests\Folder\CreateFolderRequest;
use App\Http\Requests\Folder\DeleteFolderRequest;
use App\Http\Requests\Folder\GetFolderRequest;
use App\Http\Requests\Folder\UpdateFolderRequest;
use App\Http\Resources\FolderResource;
use App\Http\Resources\ResourceResource;
use App\Http\Resources\ResourcesFromFolderResource;
use App\Http\Services\FolderService;
use App\Models\Folder;

class FolderController extends Controller
{
    protected $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return FolderResource::collection(auth()->user()->folders->where('parent_id', null));
    }

    /**
     * @param CreateFolderRequest $request
     * @return FolderResource
     */
    public function store(CreateFolderRequest $request)
    {
        $folder = $this->folderService->create($request);

        return new FolderResource($folder);
    }

    /**
     * @param GetFolderRequest $request
     * @param Folder $folder
     * @return FolderResource
     */
    public function show(GetFolderRequest $request, Folder $folder)
    {
        return new FolderResource($folder);
    }

    /**
     * @param GetFolderRequest $request
     * @param Folder $folder
     * @return ResourcesFromFolderResource
     */
    public function showResources(GetFolderRequest $request, Folder $folder)
    {
        return new ResourcesFromFolderResource([
            'resources' => ResourceResource::collection($folder->resources()->get())->toArray($request),
            'folders' => FolderResource::collection(auth()->user()->folders()
                ->where('parent_id', $folder->id)->get())
                ->toArray($request)
        ]);
    }

    /**
     * @param UpdateFolderRequest $request
     * @param Folder $folder
     * @return FolderResource
     */
    public function update(UpdateFolderRequest $request, Folder $folder)
    {
        $this->folderService->update($folder, $request->all());

        return new FolderResource($folder->fresh());
    }

    /**
     * @param DeleteFolderRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteFolderRequest $request, $id)
    {
        $result = $this->folderService->delete($id);

        return response()->json(['status' => $result]);
    }
}
