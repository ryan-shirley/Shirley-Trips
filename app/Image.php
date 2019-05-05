<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * Get the holidays for the image.
     */
    public function holidays()
    {
        return $this->hasMany('App\Holiday');
    }

    /**
     * Get the hotels for the image.
     */
    public function hotels()
    {
        return $this->hasMany('App\Hotel');
    }

    /**
     * Get the airlines for the image.
     */
    public function airlines()
    {
        return $this->hasMany('App\Airline');
    }
}
