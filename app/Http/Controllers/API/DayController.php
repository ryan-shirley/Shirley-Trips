<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Day;
Use App\Activity;
Use App\Flight;

class DayController extends Controller
{
    public function show($id)
    {
        $day = Day::findOrFail($id);

        return $day->load('hotel', 'activities');
    }

    public function flights($id)
    {
        $activity_flights = Activity::where('day_id', $id)->where('flight_id',  '!=',   null)->get();

        $flights = array();
        foreach($activity_flights as $activity_flight) {
            $flight = Flight::findOrFail($activity_flight->flight_id);
            array_push($flights, $flight);
        }

        return response()->json($flights, 200);
    }
}
