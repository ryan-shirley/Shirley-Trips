<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\Image;

class ImagesController extends Controller
{
    public function show($id)
    {
        $image = Image::findOrFail($id);

        return $image;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|image',
            'folder' => 'required|string',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $originalFileName = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();
        // $filename = date('Y-m-d-His') . '_' . $request->input('name') . '.' . $extension;
        $filename = $originalFileName . '-' . date('Y-m-d-His') . '.' . $extension;
        $path = $image->storeAs($request->input('folder'), $filename, 'public');

        $image = new Image();
        $image->path = 'storage/' . $path;
        $image->save();

        return $image;
    }

}
