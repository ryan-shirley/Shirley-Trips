<?php namespace App\Traits;

trait FlightAware
{
    public function getFlightInfo($flightNumber) {
        $queryParams = [
            'ident' => $flightNumber,
            'howMany' => 10,
            'offset' => 10
        ];
        $result = [];
        header('Content-Type: application/json');
        $response = $this->executeCurlRequest('FlightInfoStatus', $queryParams);
        return $response;
        if ($response) {
            $flightArray = json_decode($response, true);
            foreach ($flightArray['FlightInfoStatusResult']['flights'] as $flight) {
                if ($flight['actual_departure_time']['epoch'] > 0 && $flight['route']) {
                    $result['ident'] = $flight['ident'];
                    $result['faFlightID'] = $flight['faFlightID'];
                    $result['origin'] = $flight['origin']['code'];
                    $result['origin_name'] = $flight['origin']['airport_name'];
                    $result['destination'] = $flight['destination']['code'];
                    $result['destination_name'] = $flight['destination']['airport_name'];
                    $result['date'] = $flight['filed_departure_time']['date'];
                    $result['waypoints'] = getFlightRoute($flight['faFlightID']);
                    return response()->json($result);
                }
            }
        } else {
            return response()->json(['error' => 'Unable to decode flight for ' . $flightNumber], 422);
        }
    }

    /**
     * @param string $faFlightID
     * @return string
     */
    private function getFlightRoute($faFlightID) {
        $result = [];
        if ($response = executeCurlRequest('DecodeFlightRoute', array('faFlightID' => $faFlightID))) {
            $flightPoints = json_decode($response, true);
            foreach ($flightPoints['DecodeFlightRouteResult']['data'] as $point) {
                array_push($result, array('lat' => $point['latitude'], 'lng' => $point['longitude']));
            }
            return $result;
        }
        return "";
    }
    /**
     * @param string $endpoint
     * @param array $queryParams
     * @return string Empty string returned if curl request failed
     */
    private function executeCurlRequest($endpoint, $queryParams) {
        $username = env("FLIGHT_AWARE_USERNAME");
        $apiKey = env("FLIGHT_AWARE_API_KEY");
        $fxmlUrl = "https://flightxml.flightaware.com/json/FlightXML3/";
        $url = $fxmlUrl . $endpoint . '?' . http_build_query($queryParams);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        if ($result) {
            curl_close($ch);
            return $result;
        }
        return ['error'];
    }


}