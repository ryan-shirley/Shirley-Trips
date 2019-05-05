<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    /**
     * The users that belong to the Holiday.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('editPermission', 'owner')->withTimestamps();
    }

    /**
     * Get the image that owns the holiday.
     */
    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    /**
     * Get the days for the holiday.
     */
    public function days()
    {
        return $this->hasMany('App\Day');
    }
}
