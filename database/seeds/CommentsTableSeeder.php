<?php

use Illuminate\Database\Seeder;
use App\Comment;
use App\Activity;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comment = new Comment();
        $comment->title = 'Beach day with amazing view!';
        $comment->subTitle = 'Seminyak';
        $comment->description = 'This is some text that I wanted to display here';
        $comment->save();

        $activity = new Activity();
        $activity->day_id = 1;
        $activity->comment_id = $comment->id;
        $activity->order = 2;
        $activity->save();
    }
}
