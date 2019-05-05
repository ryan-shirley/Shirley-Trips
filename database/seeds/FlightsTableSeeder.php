<?php

use Illuminate\Database\Seeder;
use App\Airline;
use App\Flight;
use App\Activity;

class FlightsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airline_id = Airline::where('name', 'Lufthansa')->first()->id;

        $flight = new Flight();
        $flight->airline_id = $airline_id;
        $flight->flightNumber = 'LH 1234';
        $flight->originDayTime = '2019-06-11T13:00:00';
        $flight->destinationDayTime = '2019-06-11T14:00:00';
        $flight->duration = '60';
        $flight->originAirportShort = 'DUB';
        $flight->originAirportLong = 'Dublin';
        $flight->destinationAirportShort = 'MUN';
        $flight->destinationAirportLong = 'Munich';
        $flight->save();

        $activity = new Activity();
        $activity->day_id = 25;
        $activity->flight_id = $flight->id;
        $activity->order = 1;
        $activity->save();

    }
}
