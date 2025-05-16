<?php

namespace App\Http\Controllers;

use App\Dto\AiReponseDto;
use App\Models\AiResponse;
use App\Services\AiResponseInserter;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeminiController extends Controller
{
    protected $ai_response_inserter;
    public function __construct(AiResponseInserter $ai_response_inserter)
    {
        $this->ai_response_inserter = $ai_response_inserter;
    }
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

            $ai_response_dto = new AiReponseDto(
                $validated['prompt'],
                json_encode($data),
                $data['modelVersion'] ?? null,
                $data['usageMetadata']['totalTokenCount'] ?? null,
                200,
            );
            $this->ai_response_inserter->insert($ai_response_dto);
          

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
