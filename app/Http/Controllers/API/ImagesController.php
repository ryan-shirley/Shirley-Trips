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

}
