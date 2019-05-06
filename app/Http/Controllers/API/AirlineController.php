<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Airline;

class AirlineController extends Controller
{
    public function index()
    {
        $airline = Airline::all();

        return $airline;
    }
}
