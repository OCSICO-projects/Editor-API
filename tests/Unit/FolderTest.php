<?php

namespace Tests\Unit;

use App\Models\Resource;
use App\Models\User;
use Tests\TestCase;

class FolderTest extends TestCase
{
    public function testCreateFolder()
    {
        $user = $this->createTestUser();

        $data = [
            'name' => 'test folder'
        ];

        $response = $this->actingAs($user, 'api')->json('POST', '/folders', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "parent_id",
                "children"
            ]
        ]);
    }

    public function testGetFolder()
    {
        $user = $this->createTestUser();

        $data = [
            'name' => 'test folder'
        ];

        $response = $this->actingAs($user, 'api')->json('POST', '/folders', $data);

        $folder = $response->json('data');

        $response = $this->actingAs($user, 'api')->json('GET', '/folders/' . $folder['id']);

        $response->assertStatus(200);
        $response->assertJson(['data' => ['name' => 'test folder']]);
        $response->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "parent_id",
                "children"
            ]
        ]);
    }

    public function testGetFolderResources()
    {
        $user = $this->createTestUser();

        $data = [
            'name' => 'test folder'
        ];

        $response = $this->actingAs($user, 'api')->json('POST', '/folders', $data);

        $folder = $response->json('data');

        factory(Resource::class)
            ->create([
                    'folder_id' => $folder['id'],
                    'name' => 'Image resource',
                    'type' => 'file',
                    'subtype' => 'image',
                    'user_id' => $user->id
                ]
            );

        $response = $this->actingAs($user, 'api')->json('GET', '/folders/' . $folder['id'] . '/resources');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'type',
                    'subtype'
                ]
            ]
        ]);
        $response->assertJson(['data' => [['name' => 'Image resource']]]);
    }

    public function testUpdateFolder()
    {
        $user = $this->createTestUser();

        $data = [
            'name' => 'test folder'
        ];

        $response = $this->actingAs($user, 'api')->json('POST', '/folders', $data);

        $folder = $response->json('data');

        $newData = [
            'name' => 'test folder updated'
        ];

        $response = $this->actingAs($user, 'api')->json('PUT', '/folders/' . $folder['id'], $newData);

        $response->assertStatus(200);
        $response->assertJson(['data' => ['name' => 'test folder updated']]);
        $response->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "parent_id",
                "children"
            ]
        ]);
    }

    public function testDeleteFolder()
    {
        $user = $this->createTestUser();

        $data = [
            'name' => 'test folder'
        ];

        $response = $this->actingAs($user, 'api')->json('POST', '/folders', $data);

        $folder = $response->json('data');

        $response = $this->actingAs($user, 'api')->json('DELETE', '/folders/' . $folder['id']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 1]);
    }

    public function testGetFolderList()
    {
        $user = $this->createTestUser();

        $data = [
            'name' => 'test folder'
        ];

        $this->actingAs($user, 'api')->json('POST', '/folders', $data);

        $this->actingAs($user, 'api')->json('POST', '/folders', $data);

        $response = $this->actingAs($user, 'api')->json('GET', '/folders/');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    "id",
                    "name",
                    "parent_id",
                    "children"
                ]
            ]
        ]);
    }
}
