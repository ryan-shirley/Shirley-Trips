<?php

use Illuminate\Database\Seeder;
use App\Video;
use App\Activity;

class VideosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $video = new Video();
        $video->url = 'https://www.youtube.com/embed/Qg9eXWwfeKI';
        $video->save();

        $activity = new Activity();
        $activity->day_id = 1;
        $activity->video_id = $video->id;
        $activity->order = 3;
        $activity->save();
    }
}
