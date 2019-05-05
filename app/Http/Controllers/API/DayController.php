<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Day;

class DayController extends Controller
{
    public function show($id)
    {
        $day = Day::findOrFail($id);

        return $day->load('hotel', 'activities');
    }
}
