<?php

namespace App\Services;

use GuzzleHttp\Client;

class SummarizerService
{
    public $client;
    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'Content-type' => 'application/json'
            ],
        ]);
    }

    public function summarize(string $text)
    {
        try {
            $response = $this->client->post(config('services.gemini.base_url'), [
                'query' => [
                    'key' => config('services.gemini.key')
                ],
                'body' => json_encode([
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => 'Summarize the product reviews, i added all the reviews in comma separated way. i also want to know how the majority think about the product with the summary==>' . $text]
                            ]
                        ]
                    ]
                ])
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
