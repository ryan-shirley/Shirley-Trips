<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Traits\ImageHandler;

class CommentImageController extends Controller
{
    use Imagehandler;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|image',
            'order' => 'required|numeric',
            'commentId' => 'required|numeric|exists:comments,id',
            'holidayId' => 'required|numeric|exists:holidays,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $this->saveCommentImage($request->file('image'),  $request->input('holidayId'), $request->input('commentId'), $request->input('order'));

        if ($image != null) {
            return $image;
        }
        else {
            return response()->json('Image did not save', 422);
        }
    }
}
