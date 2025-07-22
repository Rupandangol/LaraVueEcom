<?php

namespace App\Services;

use App\DTOs\AiReponseDto;
use GuzzleHttp\Client;

class SummarizerService
{
    public $client;

    public $ai_response_inserter;

    public function __construct(AiResponseInserter $ai_response_inserter)
    {
        $this->ai_response_inserter = $ai_response_inserter;
        $this->client = new Client([
            'headers' => [
                'Content-type' => 'application/json',
            ],
        ]);
    }

    public function summarize(string $text)
    {
        $prompt = 'Summarize the product reviews, i added all the reviews in comma separated way. i also want to know how the majority think about the product with the summary==>'.$text;
        try {
            $response = $this->client->post(config('services.gemini.base_url'), [
                'query' => [
                    'key' => config('services.gemini.key'),
                ],
                'body' => json_encode([
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]),
            ]);
            $data = json_decode($response->getBody(), true);
            $ai_response_dto = new AiReponseDto(
                $prompt,
                json_encode($data),
                $data['modelVersion'] ?? null,
                $data['usageMetadata']['totalTokenCount'] ?? null,
                200,
            );
            $this->ai_response_inserter->insert($ai_response_dto);

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
