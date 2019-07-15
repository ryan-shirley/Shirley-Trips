<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Traits\ImageHandler;

class ImagesController extends Controller
{
    use Imagehandler;

    public function show($id)
    {
        $image = Image::findOrFail($id);

        return $image;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|image',
            'type' => 'required|string',
            'holidayId' => 'required|numeric|exists:holidays,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($request->input('type') == 'holiday') {
            $image = $this->saveHolidayImage($request->file('image'),  $request->input('holidayId'));
            return $image;
        }
        else if($request->input('type') == 'hotel') {
            $image = $this->saveHotelImage($request->file('image'),  $request->input('holidayId'));
            return $image;
        }
        else {
            return response()->json('Type is not valid', 422);
        }

        
    }

}
