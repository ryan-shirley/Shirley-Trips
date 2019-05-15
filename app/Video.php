<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /**
     * Get the activity for the flight.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }
}
