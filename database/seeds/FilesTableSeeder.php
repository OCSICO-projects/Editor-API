<?php

use App\Enums\ResourceSubtypes;
use App\Enums\ResourceTypes;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrFail();

        $file = factory(File::class)
            ->create([
                'user_id' => $user->id,
                'disk' => config('filesystems.default'),
                'path' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::IMAGE . '/sky.jpg',
                'directory' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::IMAGE . '/',
                'url' => config('app.url') . '/files/data/1',
                'filename' => 'sky.jpg',
                'original_filename' => 'sky.jpg',
                'extension' => 'png',
                'media_type' => 'image/jpg',
                'size' => '620'
            ]);
        $this->storeFile($file);

        $file = factory(File::class)
            ->create([
                'user_id' => $user->id,
                'disk' => config('filesystems.default'),
                'path' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::VIDEO . '/video.mp4',
                'directory' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::VIDEO . '/',
                'url' => config('app.url') . '/files/data/2',
                'filename' => 'video.mp4',
                'original_filename' => 'video.mp4',
                'extension' => 'mp4',
                'media_type' => 'video/mp4',
                'size' => '5600'
            ]);
        $this->storeFile($file);

        $file = factory(File::class)
            ->create([
                'user_id' => $user->id,
                'disk' => config('filesystems.default'),
                'path' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::VIDEO . '/preview/video.jpg',
                'directory' => '/' . $user->id . '/' . ResourceTypes::FILE . '/' . ResourceSubtypes::VIDEO . '/preview/',
                'url' => config('app.url') . '/files/data/3',
                'filename' => 'video.jpg',
                'original_filename' => 'video.jpg',
                'extension' => 'jpg',
                'media_type' => 'image/jpg',
                'size' => '320'
            ]);
        $this->storeFile($file);

        $file = factory(File::class)
            ->create([
                'user_id' => $user->id,
                'disk' => config('filesystems.default'),
                'path' => '/' . $user->id . '/' . ResourceTypes::COMPOSE . '/' . ResourceSubtypes::SLIDE . '/preview/slide.jpg',
                'directory' => '/' . $user->id . '/' . ResourceTypes::COMPOSE . '/' . ResourceSubtypes::SLIDE . '/preview/',
                'url' => config('app.url') . '/files/data/4',
                'filename' => 'slide.jpg',
                'original_filename' => 'slide.jpg',
                'extension' => 'jpg',
                'media_type' => 'image/jpg',
                'size' => '310'
            ]);
        $this->storeFile($file);
    }

    /**
     * @param File $file
     */
    private function storeFile(File $file)
    {
        if (!Storage::exists($file->path)) {
            Storage::put($file->path, Storage::disk('private')->get($file->path));
        }
    }
}
