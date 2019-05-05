<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    /**
     * Get the image for the airline.
     */
    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    /**
     * Get the flights for the airline.
     */
    public function flight()
    {
        return $this->hasMany('App\Flight');
    }
}
