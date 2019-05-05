<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Flight;

class FlightController extends Controller
{
    public function show($id)
    {
        $flight = Flight::findOrFail($id);

        return $flight->load('airline');
    }
}
