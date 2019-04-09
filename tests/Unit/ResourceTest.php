<?php

namespace Tests\Unit;

use App\Enums\ResourceSubtypes;
use App\Enums\ResourceTypes;
use App\Models\Folder;
use App\Models\Resource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    public function testCreateResource()
    {
        $user = $this->createTestUser();

        $folder = factory(Folder::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user, 'api')->json('POST', '/resources', [
            'folder_id' => $folder->id,
            'name' => 'test resource',
            'type' => ResourceTypes::FILE,
            'subtype' => ResourceSubtypes::YOUTUBE,
            'url' => 'https://www.youtube.com/watch?v=qpgT_62y5kQ'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'folder_id',
                'file',
                'preview_id',
                'preview',
                'version',
                'name',
                'relates',
                'type',
                'subtype',
                'created_at',
                'updated_at'
            ]
        ]);

        Storage::deleteDirectory($folder->id);
    }

    public function testCreateResourceFromFile()
    {
        $user = $this->createTestUser();

        $folder = factory(Folder::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user, 'api')->json('POST', '/resources/files', [
            'file' => UploadedFile::fake()->image('image.jpg'),
            'folder_id' => $folder->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'folder_id',
                'file' => [
                    'id',
                    'path',
                    'directory',
                    'url',
                    'filename',
                    'original_filename',
                    'extension',
                    'media_type',
                    'size',
                    'duration',
                    'created_at',
                    'updated_at'
                ],
                'preview_id',
                'preview',
                'version',
                'name',
                'relates',
                'type',
                'subtype',
                'created_at',
                'updated_at'
            ]
        ]);

        Storage::deleteDirectory($folder->id);
    }

    public function testGetResource()
    {
        $user = $this->createTestUser();

        $folder = factory(Folder::class)->create([
            'user_id' => $user->id
        ]);

        $resource = factory(Resource::class)
            ->create([
                    'folder_id' => $folder->id,
                    'name' => 'Image resource',
                    'type' => ResourceTypes::FILE,
                    'subtype' => ResourceSubtypes::YOUTUBE,
                    'url' => 'https://www.youtube.com/watch?v=qpgT_62y5kQ',
                    'user_id' => $user->id
                ]
            );

        $response = $this->actingAs($user, 'api')->json('GET', '/resources/' . $resource->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'folder_id',
                'file',
                'preview_id',
                'version',
                'name',
                'type',
                'subtype',
                'content',
                'created_at',
                'updated_at'
            ]
        ]);

        Storage::deleteDirectory($folder->id);
    }

    public function testGetResourceList()
    {
        $user = $this->createTestUser();

        $folder = factory(Folder::class)->create([
            'user_id' => $user->id
        ]);

        factory(Resource::class)
            ->create([
                    'folder_id' => $folder->id,
                    'name' => 'Image resource',
                    'type' => ResourceTypes::FILE,
                    'subtype' => ResourceSubtypes::YOUTUBE,
                    'url' => 'https://www.youtube.com/watch?v=qpgT_62y5kQ',
                    'user_id' => $user->id
                ]
            );

        $response = $this->actingAs($user, 'api')->json('GET', '/resources');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'folder_id',
                    'file',
                    'preview_id',
                    'version',
                    'name',
                    'type',
                    'subtype',
                    'content',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        Storage::deleteDirectory($folder->id);
    }

    public function testUpdateResource()
    {
        $user = $this->createTestUser();

        $folder = factory(Folder::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user, 'api')->json('POST', '/resources', [
            'folder_id' => $folder->id,
            'name' => 'test resource',
            'type' => ResourceTypes::FILE,
            'subtype' => ResourceSubtypes::YOUTUBE,
            'url' => 'https://www.youtube.com/watch?v=qpgT_62y5kQ'
        ]);

        $resource = $response->json('data');

        $newData = [
            'name' => 'test resource updated'
        ];

        $response = $this->actingAs($user, 'api')->json('PUT', '/resources/' . $resource['id'], $newData);

        $response->assertStatus(200);
        $response->assertJson(['data' => ['name' => 'test resource updated']]);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
                'subtype'
            ]
        ]);

        Storage::deleteDirectory($folder->id);
    }

    public function testDeleteResource()
    {
        $user = $this->createTestUser();

        $folder = factory(Folder::class)->create([
            'user_id' => $user->id
        ]);

        $resource = factory(Resource::class)
            ->create([
                    'folder_id' => $folder->id,
                    'name' => 'Image resource',
                    'type' => ResourceTypes::FILE,
                    'subtype' => ResourceSubtypes::YOUTUBE,
                    'url' => 'https://www.youtube.com/watch?v=qpgT_62y5kQ',
                    'user_id' => $user->id
                ]
            );

        $response = $this->actingAs($user, 'api')->json('DELETE', '/resources/' . $resource->id);

        $response->assertStatus(200);
        $response->assertJson(['status' => 1]);

        Storage::deleteDirectory($folder->id);
    }
}
