<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(OAuthClientsTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(FoldersTableSeeder::class);
         $this->call(FilesTableSeeder::class);
         $this->call(ResourcesTableSeeder::class);
    }
}
