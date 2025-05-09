<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GeminiController extends Controller
{
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'required|string'
        ]);
        try {
            $client = new Client();
            $response = $client->post(config('services.gemini.base_url'),  [
                'query' => [
                    'key' => config('services.gemini.key')
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $validated['prompt']]
                            ]
                        ]
                    ]
                ]),
                'timeout' => 15
            ]);
            $data = json_decode($response->getBody(), true);

            return response()->json([
                'status' => 'success',
                'data' => $data['candidates'][0]['content']['parts'][0]['text']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
