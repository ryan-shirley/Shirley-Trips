<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\User;
use App\Holiday;
use App\Image;
use App\Day;

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


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'subTitle' => 'required|string|max:50',
            'image' => 'required|file|image',
            'beginDate' => 'required|date',
            'endDate' => 'required|date',
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
        $path = $image->storeAs('holidays', $filename, 'public');

        $image = new Image();
        $image->path = 'storage/' . $path;
        $image->save();
        
        // Create Holiday
        $holiday = new Holiday();
        $holiday->title = $request->input('title');
        $holiday->subTitle = $request->input('subTitle');
        $holiday->image_id = $image->id;
        $holiday->beginDate = $request->input('beginDate');
        $holiday->endDate = $request->input('endDate');
        $holiday->save();
        $holiday->users()->attach($user, ['editPermission' => true, 'owner' => true]);

        while (strtotime($holiday->beginDate) <= strtotime($holiday->endDate)) {
            $day = new Day();
            $day->day = $holiday->beginDate;
            $day->holiday_id = $holiday->id;
            $day->save();

            $holiday->beginDate = date ("Y-m-d", strtotime("+1 day", strtotime($holiday->beginDate)));
        }

        return $holiday;
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'subTitle' => 'required|string|max:50',
            'imageId' => 'nullable|numeric|exists:images,id',
            // 'beginDate' => 'required|date',
            // 'endDate' => 'required|date',
        ]);

        // Check if user has permission to actuall update this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get User & Holiday
        $user = auth()->user();
        $holiday = Holiday::findOrFail($id);

        // If new Image id was sent
        if($request->input('imageId')) {
            // Get old image (delete later)
            $old_image = $holiday->image;
            // Same new image to holiday
            $holiday->image_id = $request->input('imageId');
        }
        
        // Update Holiday
        $holiday->title = $request->input('title');
        $holiday->subTitle = $request->input('subTitle');
        // $holiday->beginDate = $request->input('beginDate');
        // $holiday->endDate = $request->input('endDate');
        $holiday->save();

        // Delete old image if available
        if(isset($old_image)) {
            Storage::disk('public')->delete(substr($old_image->path, 8));
            $old_image->delete();
        }

        // while (strtotime($holiday->beginDate) <= strtotime($holiday->endDate)) {
        //     $day = new Day();
        //     $day->day = $holiday->beginDate;
        //     $day->holiday_id = $holiday->id;
        //     $day->save();

        //     $holiday->beginDate = date ("Y-m-d", strtotime("+1 day", strtotime($holiday->beginDate)));
        // }

        return $holiday;
    }

    public function destroy(Request $request, $id)
    {
        // Check if use has permission to delete this..
        $holiday = Holiday::findOrFail($id);

        // Delete Days
        Day::where('holiday_id',$id)->delete();

        // Delete image for holiday
        $image = Image::find($holiday->image_id);
        Storage::disk('public')->delete(substr($image->path, 8));
        $image->delete();

        // Delete Holiday
        Holiday::destroy($id);

        return response()->json(['message' => 'Successfully deleted holiday.'], 200);
    }
}
