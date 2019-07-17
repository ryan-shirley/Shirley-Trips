<?php namespace App\Traits;
use App\CommentImage;
use App\Image;

trait ImageHandler
{
    public function saveCommentImage($imageToSave, $holidayId, $commentId, $order) {
        // User
        $user =  auth()->user();

        // Image Folder
        $imageFolder = 'users/' . $user->id . '/holiday-' . $holidayId . '/comments';

        // Image file name
        $originalFileName = $imageToSave->getClientOriginalName();
        $filename = date('Y-m-d-His') . '-' . $originalFileName;

        // Save image on server
        $savedImagePath = $imageToSave->storeAs($imageFolder, $filename, 'public');

        // Save image in DB
        $image = new CommentImage();
        $image->path = 'storage/' . $savedImagePath;
        $image->order = $order;
        $image->comment_id = $commentId;
        $image->save();

        return $image;
    }

    public function saveHolidayImage($imageToSave, $holidayId) {
        // User
        $user =  auth()->user();

        // Image Folder
        $imageFolder = 'users/' . $user->id . '/holiday-' . $holidayId;

        // Image file name
        $originalFileName = str_replace(" ", "-", $imageToSave->getClientOriginalName());
        $filename = date('Y-m-d-His') . '-' . $originalFileName;

        // Save image on server
        $savedImagePath = $imageToSave->storeAs($imageFolder, $filename, 'public');

        // Save image in DB
        $image = new Image();
        $image->path = 'storage/' . $savedImagePath;
        $image->save();

        return $image;
    }

    public function saveHotelImage($imageToSave, $holidayId) {
        // User
        $user =  auth()->user();

        // Image Folder
        $imageFolder = 'users/' . $user->id . '/holiday-' . $holidayId . '/hotels';

        // Image file name
        $originalFileName = str_replace(" ", "-", $imageToSave->getClientOriginalName());
        $filename = date('Y-m-d-His') . '-' . $originalFileName;

        // Save image on server
        $savedImagePath = $imageToSave->storeAs($imageFolder, $filename, 'public');

        // Save image in DB
        $image = new Image();
        $image->path = 'storage/' . $savedImagePath;
        $image->save();

        return $image;
    }


}