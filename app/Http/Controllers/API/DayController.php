<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Day;
Use App\Activity;
Use App\Flight;
Use App\Comment;
Use App\Video;

class DayController extends Controller
{

    public function show($id)
    {
        // Find day
        $day = Day::findOrFail($id);

        // Load hotel and activities
        $day->load('hotel', 'activities');

        // Load hotel image
        if($day->hotel) {
            $day->hotel->load('image');
        }
        
        // Set empty array for all activities
        $activityList = array();

        // Load activity details
        foreach($day->activities as $activity) {

            if($activity->comment_id != null) {
                $comment = Comment::findOrFail($activity->comment_id);
                $comment->load('images');

                // Order images if array of 2 or more
                $images = json_decode(json_encode($comment->images), true);
                if(count($images) >= 2) {
                    unset($comment->images);
                    usort($images, array($this, 'order'));
                    $comment->images = $images;
                }
                $comment->order = $activity->order;
                $comment->activityId = $activity->id;
                array_push($activityList, $comment); 
            }
            else if($activity->flight_id != null) {
                $flight = Flight::findOrFail($activity->flight_id);
                $flight->load('airline');
                $flight->airline->load('image');
                $flight->order = $activity->order;
                $flight->activityId = $activity->id;
                array_push($activityList, $flight); 
            }
            else if($activity->video_id != null) {
                $video = Video::findOrFail($activity->video_id);
                $video->order = $activity->order;
                $video->activityId = $activity->id;
                array_push($activityList, $video); 
            }
        }

        // Order activities corrctly
        usort($activityList, array($this, 'order'));

        return response()->json([
            'day' => [
                'dayId' =>$day->id,
                'date' => $day->day,
                'hotel' => $day->hotel,
                'activities' => $activityList
            ]
        ], 200);
    }

    private function order($a, $b) {
        return strnatcmp($a['order'], $b['order']);
    }

}