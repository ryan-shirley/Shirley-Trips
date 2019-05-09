<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
Use App\Flight;
use App\Activity;
use DateTime;

class FlightController extends Controller
{
    public function show($id)
    {
        $flight = Flight::findOrFail($id);

        return $flight->load('airline');
    }

    public function store(Request $request)
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

        $flight = new Flight();
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
