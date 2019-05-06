<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\Comment;
Use App\Activity;

class CommentController extends Controller
{
    public function show($id)
    {
        $comment = Comment::findOrFail($id);

        return $comment->load('images');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'subTitle' => 'required|string|max:50',
            'dayId' => 'required|numeric|exists:days,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $comment = new Comment();
        $comment->title = $request->input('title');
        $comment->subTitle = $request->input('subTitle');
        $comment->save();

        $activity = new Activity();
        $activity->day_id = $request->input('dayId');
        $activity->comment_id = $comment->id;
        $activity->order = 1;
        $activity->save();

        return $comment;
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'subTitle' => 'required|string|max:50',
            'dayId' => 'required|numeric|exists:days,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $comment = Comment::findOrFail($id);
        $comment->title = $request->input('title');
        $comment->subTitle = $request->input('subTitle');
        $comment->save();

        return $comment;
    }
}
