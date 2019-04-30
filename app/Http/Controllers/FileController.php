<?php

namespace App\Http\Controllers;

use App\Http\Middleware\VideoStream;
use App\Http\Resources\FileResource;
use App\Http\Services\FileService;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    protected $fileService;

    /**
     * FileController constructor.
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return FileResource::collection(auth()->user()->files);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO to realize method
    }

    /**
     * Display the specified resource.
     *
     * @param  File $file
     * @return FileResource
     */
    public function show(File $file)
    {
        return new FileResource($file);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  File $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //TODO to realize method
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  File $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //TODO to realize method
    }

    /**
     * @param File $file
     * @return mixed
     */
    public function data(File $file)
    {
        $filePath = storage_path('app/' . config('filesystems.default') . $file->path);
        if ($file->media_type === 'video/mp4') {
            $stream = new VideoStream($filePath);
            $stream->start();
        } else {
            return Storage::response($file->path);
        }
    }
}
