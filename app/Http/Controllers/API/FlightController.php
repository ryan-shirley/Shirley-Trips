<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\Flight;
use App\Activity;
use DateTime;
use App\Traits\FlightAware;

class FlightController extends Controller
{
    use FlightAware;

    public function show($id)
    {
        $flight = Flight::findOrFail($id);

        return $flight->load('airline', 'activity');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'airlineId' => 'required|numeric|exists:airlines,id',
            'flightNumber' => 'required|string|max:50',
            'originDate' => 'required|date',
            'destinationDate' => 'required|date',
            'dayId' => 'required|numeric|exists:days,id',
            'connectingFlightId' => 'nullable|numeric|exists:flights,id',
            'layoverLength' => 'required_with:connectingFlightId',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get live flight Info
        $liveFlightInfoFull = json_decode($this->getFlightInfo($request->input('flightNumber')), true);

        if($this->getFlightInfo($request->input('flightNumber')) == "Too many requests in a given amount of time.\n") {
            return response()->json(['error', 'Too many requests in a given time. Please try again in a minute.'], 422);
        }

        $liveFlightInfo = $liveFlightInfoFull['FlightInfoStatusResult']['flights'][0];

        // Create Flights
        $flight = new Flight();
        $flight->airline_id = $request->input('airlineId');
        $flight->flightNumber = $request->input('flightNumber');
        $flight->originDayTime = $request->input('originDate') . ' ' . date('H:i', $liveFlightInfo['filed_departure_time']['localtime']);
        $flight->destinationDayTime = $request->input('destinationDate') . ' ' . date('H:i', $liveFlightInfo['filed_arrival_time']['localtime']);
        $flight->duration = $liveFlightInfo['filed_ete'];
        $flight->originAirportShort = $liveFlightInfo['origin']['alternate_ident'];
        $flight->originAirportLong = $liveFlightInfo['origin']['city'];
        $flight->destinationAirportShort = $liveFlightInfo['destination']['alternate_ident'];
        $flight->destinationAirportLong = $liveFlightInfo['destination']['city'];
        
        // Layover + Connecting Flight
        $flight->connectingFlightId = $request->input('connectingFlightId');
        $flight->layoverLength = $request->input('layoverLength');
        $flight->save();

        $activity = new Activity();
        $activity->day_id = $request->input('dayId');
        $activity->flight_id = $flight->id;
        $activity->order = 1;
        $activity->save();

        return $flight;
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'airlineId' => 'required|numeric|exists:airlines,id',
            'flightNumber' => 'required|string|max:50',
            'originDate' => 'required|date',
            'originTime' => 'required|date_format:H:i',
            'originAirportShort' => 'required|string|max:50',
            'originAirportLong' => 'required|string|max:150',
            'destinationDate' => 'required|date',
            'destinationTime' => 'required|date_format:H:i',
            'destinationAirportShort' => 'required|string|max:50',
            'destinationAirportLong' => 'required|string|max:150',
            'dayId' => 'required|numeric|exists:days,id',
            'connectingFlightId' => 'nullable|numeric|exists:flights,id',
            'layoverLength' => 'required_with:connectingFlightId',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $flight = Flight::findOrFail($id);
        $flight->airline_id = $request->input('airlineId');
        $flight->flightNumber = $request->input('flightNumber');
        $flight->originDayTime = new DateTime($request->input('originDate') . ' ' . $request->input('originTime'));
        $flight->destinationDayTime =  new DateTime($request->input('destinationDate') . ' ' . $request->input('destinationTime'));

        // Need to calculate the duration here..
        $flight->duration = 0;

        $flight->originAirportShort = $request->input('originAirportShort');
        $flight->originAirportLong = $request->input('originAirportLong');
        $flight->destinationAirportShort = $request->input('destinationAirportShort');
        $flight->destinationAirportLong = $request->input('destinationAirportLong');
        
        // Layover + Connecting Flight
        $flight->connectingFlightId = $request->input('connectingFlightId');
        $flight->layoverLength = $request->input('layoverLength');
        $flight->save();

        return $flight;
    }

    public function destroy(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|numeric|exists:activities,id',
        ]);

        // Check if user has permission to actuall save this... ******

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Activity::destroy($request->input('activity_id'));
        
        $flight = Flight::findOrFail($id);
        $flight->delete();

        return response()->json(['message' => 'Successfully deleted flight.'], 200);
    }
}
