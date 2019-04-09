<?php

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;

class FoldersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrFail();

        $parent = Folder::create([
            'name' => 'video',
            'user_id' => $user->id
        ]);

        Folder::create([
            'name' => 'subfolder Video',
            'parent_id' => $parent->id,
            'user_id' => $user->id
        ]);

        Folder::create([
            'name' => 'images',
            'user_id' => $user->id
        ]);

        Folder::create([
            'name' => 'youtube',
            'user_id' => $user->id
        ]);

        Folder::create([
            'name' => 'slides',
            'user_id' => $user->id
        ]);
    }
}
