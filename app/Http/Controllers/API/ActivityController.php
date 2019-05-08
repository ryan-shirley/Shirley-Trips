<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\Activity;

class ActivityController extends Controller
{

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $activity = Activity::findOrFail($id);
        $activity->order = $request->input('order');
        $activity->save();

        return $activity;
    }

}
