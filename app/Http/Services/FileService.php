<?php

namespace App\Http\Services;

use App\Enums\ResourceSubtypes;
use App\Enums\ResourceTypes;
use App\Http\Services\Previews\PreviewFactory;
use App\Jobs\GeneratePreviewProcess;
use App\Jobs\UploadFileProcess;
use App\Models\File;
use App\Models\Resource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public $subtype;
    public $file;
    public $preview;

    /**
     * @param $request
     * @return File
     */
    public function create($request)
    {
        /** @var UploadedFile $file */
        $file = $this->file = $request->file('file');
        $mimeType = $file->getMimeType();
        $subtype = $this->subtype = ResourceSubtypes::getSubtypeByMimeType($mimeType);
        $directory = $this->getDirBySubtype($subtype);

        /** @var File $file */
        $fileModel = File::create([
            'user_id' => auth()->id(),
            'disk' => config('filesystems.default'),
            'url' => config('app.url') . $directory . $file->hashName(),
            'path' => $directory . $file->hashName(),
            'directory' => $directory,
            'filename' => $file->hashName(),
            'original_filename' => $file->getClientOriginalName(),
            'extension' => $file->extension(),
            'media_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);
        UploadFileProcess::dispatch($file->get(), $fileModel, $subtype);

        return $fileModel;
    }

    /**
     * @param $fileModel
     * @param $resource
     */
    public function generatePreview($fileModel, $resource)
    {
        GeneratePreviewProcess::dispatch($this->file->get(), $fileModel, $this->subtype, $resource);
    }

    /**
     * @param Resource $resource
     * @return File
     */
    public function createPreviewFromResource(Resource $resource)
    {
        $preview = PreviewFactory::make($resource->subtype);

        return $preview->create($resource);
    }

    /**
     * @param File $file
     */
    public function delete(File $file)
    {
        Storage::delete($file->path);
        $file->delete();
    }

    /**
     * @param File $file
     */
    public function deleteModel(File $file)
    {
        $file->delete();
    }

    /**
     * @param string $subtype
     * @param string $type
     * @return string
     */
    public static function getDirBySubtypeAndType(string $subtype, string $type)
    {
        return '/' . auth()->id() . '/' . $type . '/' . $subtype . '/';
    }

    /**
     * @param string $subtype
     * @param string $type
     * @return string
     */
    public static function getPreviewDirBySubtypeAndType(string $subtype, string $type)
    {
        return self::getDirBySubtypeAndType($subtype, $type) . 'preview/';
    }

    /**
     * @param string $subtype
     * @return string
     */
    private function getDirBySubtype(string $subtype)
    {
        return '/' . auth()->id() . '/' . ResourceTypes::FILE . '/' . $subtype . '/';
    }
}
