<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Comment;

class CommentController extends Controller
{
    public function show($id)
    {
        $comment = Comment::findOrFail($id);

        return $comment->load('images');
    }
}
