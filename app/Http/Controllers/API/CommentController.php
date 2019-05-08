<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\Comment;
Use App\Activity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

    public function destroy(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|numeric|exists:activities,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Activity::destroy($request->input('activity_id'));

        $images = DB::table('comment_images')->where('comment_id','=', $id)->pluck('path');
        foreach($images as $path) {
            Storage::disk('public')->delete(substr($path, 8));
        }
        DB::table('comment_images')->where('comment_id', '=', $id)->delete();
        
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Successfully deleted comment.'], 200);
    }
}
