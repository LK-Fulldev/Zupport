<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class CallRestGoogle
{
    public function callRestaurants($searchWord)
    {
        try {
            $params = [
                'key' => 'AIzaSyAbtnVijr4XgFiSWnUyYH3I_D0d5NZeCQo',
                'query' => 'restaurants in '.$searchWord,
                'radius' => 500
            ];

            $client = new Client();
            $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/place/textsearch/json', ['query' => $params]);
            $results = json_decode($response->getBody()->getContents(), true)['results'];

            return [
                'status' => true,
                'data' => $results
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ];
        }
    }
}
