<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
Use App\Hotel;
Use App\Day;
Use App\Holiday;
use Validator;
use App\Image;
use App\Traits\ImageHandler;

class HotelController extends Controller
{
    use Imagehandler;

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
        $image = $this->saveHotelImage($request->file('image'), $request->input('holidayId'));
        
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'location' => 'required|string|max:50',
            'imageId' => 'nullable',
            'dayCheckInId' => 'required|numeric|exists:days,id',
            'dayCheckOutId' => 'required|numeric|exists:days,id',
            'holidayId' => 'required|numeric|exists:holidays,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update Hotel
        $hotel = Hotel::findOrFail($id);
        $hotel->name = $request->input('name');
        $hotel->location = $request->input('location');

        // If new Image id was sent
        if($request->input('imageId')) {
            // Get old image (delete later)
            $old_image = $hotel->image;
            // Same new image to holiday
            $hotel->image_id = $request->input('imageId');
        }

        $hotel->checkIn = Day::find($request->input('dayCheckInId'))->day;
        $hotel->checkOut = Day::find($request->input('dayCheckOutId'))->day;
        $hotel->save();

        // Delete old image if available
        if(isset($old_image)) {
            Storage::disk('public')->delete(substr($old_image->path, 8));
            $old_image->delete();
        }

        // Update Days for hotel
        // Remove old days
        DB::table('days')
            ->where('hotel_id', $hotel->id)
            ->update(['hotel_id' => null]);

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

        return $hotel;
    }

    public function destroy(Request $request, $id)
    {
        // Check if use has permission to delete this..
        $hotel = Hotel::findOrFail($id);

        // Update days to show no hotel
        DB::table('days')
            ->where('hotel_id', $hotel->id)
            ->update(['hotel_id' => null]);

        // Delete image for hotel
        $image = Image::find($hotel->image_id);
        Storage::disk('public')->delete(substr($image->path, 8));
        $image->delete();

        // Delete Hotel
        $hotel->delete();

        return response()->json(['message' => 'Successfully deleted hotel.'], 200);
    }
}
