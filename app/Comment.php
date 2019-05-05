<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Get the images for the comment.
     */
    public function images()
    {
        return $this->hasMany('App\CommentImage');
    }

    /**
     * Get the activity for the comment.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }
}
