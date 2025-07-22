<?php

namespace App\Http\Controllers;

use App\DTOs\AiReponseDto;
use App\Services\AiResponseInserter;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'prompt' => 'required|string',
        ]);
        try {
            $client = new Client;
            $response = $client->post(config('services.gemini.base_url'), [
                'query' => [
                    'key' => config('services.gemini.key'),
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $validated['prompt']],
                            ],
                        ],
                    ],
                ]),
                'timeout' => 15,
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
                'data' => $data['candidates'][0]['content']['parts'][0]['text'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function aiAnalytics(Request $request)
    {
        try {
            $query = DB::table('ai_responses');
            if ($request->filled('admin_id')) {
                $query->where('admin_id', $request->get('admin_id'));
            }
            if ($request->filled('prompt')) {
                $query->where('prompt', 'like', '%'.$request->get('prompt').'%');
            }
            // top prompt

            $top_prompt = (clone $query)
                ->select('prompt', DB::raw('COUNT(*) as total'))
                ->groupBy('prompt')
                ->orderByDesc('total')
                ->limit(3)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'fetched successfully',
                'data' => [
                    'top_prompt' => $top_prompt,
                    'ai_responses' => $query->paginate(10),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function export()
    {
        $filename = 'ai_response'.Carbon::now()->format('YmdHis');
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Ai Reponse Table',
            ]);
            $header = [
                'id',
                'prompt',
                'response',
                'summary',
                'model',
                'token_used',
                'request_id',
                'status_code',
                'time',
            ];

            fputcsv($handle, $header);
            DB::table('ai_responses')->orderBy('id', 'desc')->chunk(100, function ($items) use ($handle) {
                foreach ($items as $item) {
                    fputcsv($handle, [
                        $item->id ?? '',
                        $item->prompt ?? '',
                        $item->summary ?? '',
                        $item->model ?? '',
                        $item->token_used ?? '',
                        $item->request_id ?? '',
                        $item->status_code ?? '',
                        $item->time ?? '',
                    ]);
                }
            });
            fclose($handle);
        }, 200, $headers);
    }
}
