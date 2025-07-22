<?php

namespace App\Http\Controllers;

use App\Models\WeatherData;
use App\Models\WeatherLocation;
use Carbon\Carbon;
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
            if (! WeatherData::whereDate('weather_date_time', '=', Carbon::now(self::myTimezone)->format('Y-m-d'))->exists()) {

                $client = new Client;
                try {
                    $response = $client->get(config('weather.base_url'), [
                        'query' => [
                            'key' => config('weather.api_key'),
                            'place_id' => $place_id,
                        ],
                    ]);
                    $data = json_decode($response->getBody(), true);
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    // Handle 4xx errors
                    return response()->json([
                        'status' => 'error',
                        'message' => 'API request failed: '.$e->getResponse()->getBody()->getContents(),
                    ], $e->getCode());
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    // Handle network errors
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to connect to weather API.',
                    ], 500);
                }
                $weatherLocation = WeatherLocation::firstOrCreate([
                    'timezone' => $data['timezone'],
                ], [
                    'lat' => $data['lat'],
                    'lon' => $data['lon'],
                    'units' => $data['units'],
                    'elevation' => $data['elevation'],
                ]);
                foreach ($data['hourly']['data'] as $item) {
                    if (! isset($item['date'], $item['weather'], $item['summary'], $item['temperature'])) {
                        // do something later
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
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function getPlaceId() {}

    public function weatherAnalytics(Request $request)
    {
        // total_weather_count
        // top_3_weather
        // top_3_temperature
        // top_3_wind_speed

        $query = WeatherData::query();
        if ($request->filled('weather')) {
            $query->where('weather', $request->get('weather'));
        }
        if ($request->filled('temperature')) {
            $query->where('temperature', $request->get('temperature'));
        }
        if ($request->filled('wind_speed')) {
            $query->where('wind_speed', $request->get('wind_speed'));
        }
        if ($request->filled('wind_dir')) {
            $query->where('wind_dir', $request->get('wind_dir'));
        }
        if ($request->filled('weather_location_id')) {
            $query->where('weather_location_id', $request->get('weather_location_id'));
        }
        if ($request->filled('date')) {
            $query->whereDate('weather_date_time', $request->get('date'));
        }
        $data = $query;

        $total_count = (clone $query)->count();
        $top_weather = (clone $query)
            ->select('weather', DB::raw('Count(*) as total'))
            ->groupBy('weather')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();

        $top_temperature = (clone $query)
            ->select('temperature', DB::raw('COUNT(*) as total'))
            ->groupBy('temperature')
            ->orderBy('total')
            ->limit(3)
            ->get();

        $top_wind_speed = (clone $query)
            ->select('wind_speed', DB::raw('COUNT(*) as total'))
            ->groupBy('wind_speed')
            ->orderBy('total')
            ->limit(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Analytics fetched successfully',
            'data' => [
                'total_count' => $total_count,
                'top_weather' => $top_weather,
                'top_temperature' => $top_temperature,
                'top_wind_speed' => $top_wind_speed,
                'data' => $data->latest('id')->paginate(10),
            ],
        ]);
    }
}
