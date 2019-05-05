<?php

use Illuminate\Database\Seeder;
use App\Image;
use App\Airline;

class AirlinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = Image::where('id', 1)->first();

        $airline = new Airline();
        $airline->name = 'Lufthansa';
        $airline->image_id = $image->id;
        $airline->save();
    }
}
