<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    /**
     * Get the holiday for the day.
     */
    public function holiday()
    {
        return $this->belongsTo('App\Holiday');
    }

    /**
     * Get the hotel for the day.
     */
    public function hotel()
    {
        return $this->belongsTo('App\Hotel');
    }

    /**
     * Get the activities for the day.
     */
    public function activities()
    {
        return $this->hasMany('App\Activity');
    }
}
