<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\CommentImage;

class CommentImageController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|image',
            'order' => 'required|numeric',
            'commentId' => 'required|numeric|exists:comments,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $originalFileName = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();
        $filename = date('Y-m-d-His') . '-' . $originalFileName;
        $path = $image->storeAs('comments', $filename, 'public');

        $image = new CommentImage();
        $image->path = 'storage/' . $path;
        $image->order = $request->input('order');
        $image->comment_id = $request->input('commentId');
        $image->save();

        return $image;
    }
}
