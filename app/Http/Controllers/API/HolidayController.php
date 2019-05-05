<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Holiday;

class HolidayController extends Controller
{

    public function index()
    {
        $user =  auth()->user();
        $u = auth()->user();
        $holidays = $u->holidays->load('image');

        return response()->json($holidays, 200);
    }

    public function show($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->load('users', 'days', 'image');

        return $holiday;
    }
}
