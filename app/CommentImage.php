<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentImage extends Model
{
    /**
     * Get the comment for the image.
     */
    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }
}
