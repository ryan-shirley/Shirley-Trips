<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Airline;
use Validator;

class AirlineController extends Controller
{
    public function index()
    {
        $airline = Airline::all();

        return $airline->load('image');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'imageId' => 'required|numeric|exists:images,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $airline = new Airline();
        $airline->name = $request->input('name');
        $airline->image_id = $request->input('imageId');
        $airline->save();

        return $airline;
    }
}
