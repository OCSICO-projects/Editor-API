<?php

namespace Tests\Unit;

use App\Enums\ResourceSubtypes;
use App\Enums\ResourceTypes;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilesTest extends TestCase
{
    public function testGetFile()
    {
        $user = $this->createTestUser();

        $file = factory(File::class)
            ->create([
                    'disk' => 'test',
                    'path' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::IMAGE . '/sky.jpg',
                    'directory' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::IMAGE . '/',
                    'url' => config('app.url') . '/files/data/1',
                    'filename' => 'sky.jpg',
                    'original_filename' => 'sky.jpg',
                    'extension' => 'png',
                    'media_type' => 'image/jpg',
                    'size' => '1',
                    'user_id' => $user->id
                ]
            );

        $response = $this->actingAs($user, 'api')->json('GET', '/files/' . $file->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                "id",
                "path",
                "directory",
                "url",
                "filename",
                "original_filename",
                "extension",
                "media_type",
                "size",
                "duration",
                "created_at",
                "updated_at"
            ]
        ]);

        Storage::deleteDirectory($user->id);
    }

    public function testGetFileList()
    {
        $user = $this->createTestUser();

        factory(File::class)
            ->create([
                    'disk' => 'test',
                    'path' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::IMAGE . '/sky.jpg',
                    'directory' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::IMAGE . '/',
                    'url' => config('app.url') . '/files/data/1',
                    'filename' => 'sky.jpg',
                    'original_filename' => 'sky.jpg',
                    'extension' => 'png',
                    'media_type' => 'image/jpg',
                    'size' => '1',
                    'user_id' => $user->id
                ]
            );

        $response = $this->actingAs($user, 'api')->json('GET', '/files');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",
                    "path",
                    "directory",
                    "url",
                    "filename",
                    "original_filename",
                    "extension",
                    "media_type",
                    "size",
                    "duration",
                    "created_at",
                    "updated_at"
                ]
            ]
        ]);

        Storage::deleteDirectory($user->id);
    }

    public function testGetFileData()
    {
        $user = $this->createTestUser();

        $file = factory(File::class)
            ->create([
                    'disk' => 'test',
                    'path' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::IMAGE . '/sky.jpg',
                    'directory' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::IMAGE . '/',
                    'url' => config('app.url') . '/files/data/1',
                    'filename' => 'sky.jpg',
                    'original_filename' => 'sky.jpg',
                    'extension' => 'png',
                    'media_type' => 'image/jpg',
                    'size' => '1',
                    'user_id' => $user->id
                ]
            );

        UploadedFile::fake()->image($file->filename)->storeAs($file->directory, $file->filename);

        $response = $this->actingAs($user, 'api')->json('GET', '/files/data/' . $file->id );

        $response->assertStatus(200);

        Storage::deleteDirectory($user->id);
    }
}
