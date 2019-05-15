<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\Video;
Use App\Activity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function show($id)
    {
        $video = Video::findOrFail($id);

        return $video;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'videoUrl' => 'required|string',
            'dayId' => 'required|numeric|exists:days,id'
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $video = new Video();
        $video->url = $request->input('videoUrl');
        $video->save();

        $activity = new Activity();
        $activity->day_id = $request->input('dayId');
        $activity->video_id = $video->id;
        $activity->order = 99;
        $activity->save();

        return $video;
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'videoUrl' => 'required|string'
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $video = Video::findOrFail($id);
        $video->url = $request->input('videoUrl');
        $video->save();

        return $video;
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
        
        $video = Video::findOrFail($id);
        $video->delete();

        return response()->json(['message' => 'Successfully deleted video.'], 200);
    }

}
