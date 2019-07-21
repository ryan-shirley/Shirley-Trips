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
use Illuminate\Foundation\Inspiring;
use App\Traits\ImageHandler;
use DB;

class HolidayController extends Controller
{
    use Imagehandler;

    public function index()
    {
        // Get user, all holidays and inspiration
        $user =  auth()->user();
        $holidays = $user->holidays->load('image');
        $inspiration = Inspiring::quote();

        // Current date
        $currentDate = date("Y-m-d");

        // Array for various holiday times
        $liveHolidays = array();
        $upcommingHolidays = array();
        $pastHolidays = array();

        // Sort holidays
        foreach($holidays as $holiday) {
            // Holiday Beginning and end dates
            $beginningDate = $holiday->beginDate;
            $endDate = $holiday->endDate;

            // Holiday in the past
            if ($currentDate > $beginningDate && $currentDate > $endDate) {
                array_push($pastHolidays, $holiday); 
            }
            else if ($currentDate >= $beginningDate && $currentDate < $endDate) {
                array_push($liveHolidays, $holiday);
            }
            else if ($currentDate < $beginningDate) {
                array_push($upcommingHolidays, $holiday);
            }
        }

        return response()->json([
            'user_first_name' => $user->first_name,
            'inspiration' => $inspiration,
            'live_holidays' => $liveHolidays,
            'upcomming_holidays' => $upcommingHolidays,
            'past_holidays' => $pastHolidays
        ], 200);
    }

    public function show($id)
    {
        $user =  auth()->user();

        $holiday = Holiday::findOrFail($id);
        $holiday->load('users', 'days', 'image');

        return ['holiday' => $holiday, 'user' => $user];
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

        // Save Image
        $nextHolidayId = DB::select("SHOW TABLE STATUS LIKE 'holidays'")[0]->Auto_increment;
        $image = $this->saveHolidayImage($request->file('image'), $nextHolidayId);
        
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
            'imageId' => 'nullable|numeric|exists:images,id'
        ]);


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

    public function updatePermissions(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'users' => 'required|array'
        ]);

        // Check if user has permission to actuall update this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Holiday
        $holiday = Holiday::findOrFail($id);

        $users = $request->input('users');

        foreach($users as $user) {
            $u = User::findOrFail($user['id']);
            $holiday->users()->detach($u);

            if($user['can_edit']) {
                $u = User::findOrFail($user['id']);
                $holiday->users()->attach($u, ['editPermission' => true]);
            }
            else if ($user['can_view']) {
                $u = User::findOrFail($user['id']);
                $holiday->users()->attach($u, ['editPermission' => false]);
            }
        }

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
