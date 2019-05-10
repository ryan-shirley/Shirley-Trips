<?php

use Illuminate\Database\Seeder;
use App\Image;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = new Image();
        $image->path = 'storage/holidays/holiday.jpg';
        $image->save();

        $image = new Image();
        $image->path = 'storage/airlines/Lufthansa-logo.jpg';
        $image->save();

        $image = new Image();
        $image->path = 'storage/hotels/hotel.jpg';
        $image->save();
    }
}
