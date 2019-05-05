<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    /**
     * Get the image for the hotel.
     */
    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    /**
     * Get the days for the hotel.
     */
    public function days()
    {
        return $this->hasMany('App\Day');
    }
}
