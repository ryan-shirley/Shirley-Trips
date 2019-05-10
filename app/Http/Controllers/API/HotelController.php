<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
Use App\Hotel;
Use App\Day;
Use App\Image;
Use App\Holiday;
use Validator;

class HotelController extends Controller
{
    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);

        return $hotel->load('image');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'location' => 'required|string|max:50',
            'image' => 'required|file|image',
            'dayCheckInId' => 'required|numeric|exists:days,id',
            'dayCheckOutId' => 'required|numeric|exists:days,id',
            'holidayId' => 'required|numeric|exists:holidays,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get User
        $user = auth()->user();

        // Upload Image
        $image = $request->file('image');
        $originalFileName = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();
        $filename = $originalFileName . '-' . date('Y-m-d-His') . '.' . $extension;
        $path = $image->storeAs('hotels', $filename, 'public');

        $image = new Image();
        $image->path = 'storage/' . $path;
        $image->save();
        
        // Create Hotel
        $hotel = new Hotel();
        $hotel->name = $request->input('name');
        $hotel->location = $request->input('location');
        $hotel->image_id = $image->id;

        $hotel->checkIn = Day::find($request->input('dayCheckInId'))->day;
        $hotel->checkOut = Day::find($request->input('dayCheckOutId'))->day;
        $hotel->save();

        // Add Hotel to days for checkin to checkout.
        $holiday = Holiday::where('id', $request->input('holidayId'))->first();
        $holiday_id = $holiday->id;
        $all_holiday_days = $holiday->days;

        while (strtotime($hotel->checkIn) <= strtotime("-1 day", strtotime($hotel->checkOut))) {

            foreach ($all_holiday_days as $d) {
                // If holiday day = a day when in the hotel update
                if($d->day == $hotel->checkIn) {
                    $day_toUpdate = Day::where('id', $d->id)->first();
                    $day_toUpdate->hotel_id = $hotel->id;
                    $day_toUpdate->save();
                }
            }

            $hotel->checkIn = date ("Y-m-d", strtotime("+1 day", strtotime($hotel->checkIn)));
        }

        return $holiday;
    }
}
