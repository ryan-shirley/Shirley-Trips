<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * Get the flights for the activity.
     */
    public function flights()
    {
        return $this->hasMany('App\Flight');
    }

    /**
     * Get the comments for the activity.
     */
    public function comments()
    {
        return $this->hasMany('App\Comments');
    }

    /**
     * Get the day for the activity.
     */
    public function day()
    {
        return $this->belongsTo('App\Day');
    }
}
