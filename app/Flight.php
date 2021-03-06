<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    /**
     * Get the airline for the flight.
     */
    public function airline()
    {
        return $this->belongsTo('App\Airline');
    }

    /**
     * Get the activity for the flight.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity', 'id', 'flight_id');
    }

    /**
     * Get the connecting flight for the flight.
     */
    public function connectingFlight()
    {
        return $this->hasOne('App\Flight', 'id');
    }
}
