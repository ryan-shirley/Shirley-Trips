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
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ImagesTableSeeder::class);
        $this->call(HolidaysTableSeeder::class);
        $this->call(HotelsTableSeeder::class);
        $this->call(AirlinesTableSeeder::class);
        $this->call(FlightsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(CommentImagesTableSeeder::class);
        $this->call(VideosTableSeeder::class);
    }
}
