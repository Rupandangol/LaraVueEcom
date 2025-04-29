<?php

namespace App\Http\Controllers;

use App\Models\WeatherData;
use App\Models\WeatherLocation;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeatherController extends Controller
{
    protected const myTimezone = 'Asia/Kathmandu';

    public function fetchWeather($place_id)
    {
        DB::beginTransaction();
        try {
            if (!WeatherData::whereDate('weather_date_time', '=', Carbon::now(self::myTimezone)->format('Y-m-d'))->exists()) {

                $client = new Client();
                try {
                    $response = $client->get(config('weather.base_url'), [
                        'query' => [
                            'key' => config('weather.api_key'),
                            'place_id' => $place_id,
                        ]
                    ]);
                    $data = json_decode($response->getBody(), true);
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    // Handle 4xx errors
                    return response()->json([
                        'status' => 'error',
                        'message' => 'API request failed: ' . $e->getResponse()->getBody()->getContents(),
                    ], $e->getCode());
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    // Handle network errors
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to connect to weather API.',
                    ], 500);
                }
                $weatherLocation = WeatherLocation::firstOrCreate([
                    'timezone' => $data['timezone']
                ], [
                    'lat' => $data['lat'],
                    'lon' => $data['lon'],
                    'units' => $data['units'],
                    'elevation' => $data['elevation'],
                ]);
                foreach ($data['hourly']['data'] as $item) {
                    if (!isset($item['date'], $item['weather'], $item['summary'], $item['temperature'])) {
                        //do something later
                    }

                    WeatherData::firstOrCreate([
                        'weather_date_time' => $item['date'],
                    ], [
                        'weather' => $item['weather'],
                        'summary' => $item['summary'],
                        'temperature' => $item['temperature'],
                        'wind_speed' => $item['wind']['speed'],
                        'wind_dir' => $item['wind']['dir'],
                        'wind_angle' => $item['wind']['angle'],
                        'cloud_cover_total' => $item['cloud_cover']['total'],
                        'precipitation_total' => $item['precipitation']['total'],
                        'precipitation_type' => $item['precipitation']['type'],
                        'weather_location_id' => $weatherLocation->id,
                    ]);
                }
            }
            DB::commit();
            $weatherLocationData = WeatherLocation::where('timezone', self::myTimezone)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Fetched Successfullly',
                'data' => [
                    'location_data' => $weatherLocationData,
                    'today_data' => WeatherData::where(['weather_location_id' => $weatherLocationData->id])
                        ->where('weather_date_time', '<=', Carbon::now(self::myTimezone)->format('Y-m-d H:i:s'))
                        ->orderBy('id', 'desc')
                        ->first(),
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function getPlaceId() {
    
    }
}
