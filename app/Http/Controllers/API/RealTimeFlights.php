<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FlightAware;
use App\Flight;

class RealTimeFlights extends Controller
{
    use FlightAware;

    public function show($flightId)
    {
        $static_flight = Flight::findOrFail($flightId);
        $static_flight_departure_day = date('Y-m-d', strtotime($static_flight->originDayTime));
        $ident = $static_flight->flightNumber;

        $response = $this->getFlightInfo($ident);

        if($response == "Too many requests in a given amount of time.\n") {
            return response()->json($response, 422);
        }

        // Get Flight
        $flights = json_decode($response, true)['FlightInfoStatusResult']['flights'];

        foreach($flights as $flight) {
            $flight_date = date_create_from_format('d/m/Y', $flight['filed_departure_time']['date'])->format('Y-m-d');

            // Find on flight on right day
            if($flight_date == $static_flight_departure_day) {
                return $flight;
            }
        }
        
        return 'Flight Status not available';
    }

}
