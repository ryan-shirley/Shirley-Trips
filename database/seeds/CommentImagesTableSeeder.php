<?php

use Illuminate\Database\Seeder;
use App\CommentImage;

class CommentImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = new CommentImage();
        $image->path = 'storage/comments/comment.jpg';
        $image->order = 1;
        $image->comment_id = 1;
        $image->save();
    }
}
