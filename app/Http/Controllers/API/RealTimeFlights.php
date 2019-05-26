<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FlightAware;

class RealTimeFlights extends Controller
{
    use FlightAware;

    public function show($ident)
    {
        $response = $this->getFlightInfo($ident);

        if($response == "Too many requests in a given amount of time.\n") {
            return response()->json($response, 422);
        }
        
        return $response;
    }

}
